<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use TomatoPHP\FilamentSaasPanel\Mail\TeamInvitation;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->guard = config('filament-saas-panel.auth_guard');
    $this->owner = User::factory()->create();
    $this->team = $this->owner->teams()->create([
        'user_id' => $this->owner->id,
        'name' => 'Team 1',
        'personal_team' => false,
    ]);
    $this->invitee = User::factory()->create(['email' => 'Invitee@Example.test']);
    $this->stranger = User::factory()->create();
    $this->invitation = $this->team->teamInvitations()->create([
        'email' => 'invitee@example.test',
        'role' => 'editor',
    ]);
});

function inviteUrl(string $action, $invitation): string
{
    return URL::signedRoute("team-invitations.{$action}", ['invitation' => $invitation->id]);
}

it('lets the invitee accept through a signed link', function () {
    actingAs($this->invitee, $this->guard);

    get(inviteUrl('accept', $this->invitation))->assertOk();
    assertDatabaseHas('team_invitations', ['id' => $this->invitation->id]);

    post(inviteUrl('accept', $this->invitation))->assertRedirect();

    assertDatabaseMissing('team_invitations', ['id' => $this->invitation->id]);
    assertDatabaseHas('team_user', ['team_id' => $this->team->id, 'user_id' => $this->invitee->id, 'role' => 'editor']);
});

it('rejects another user accepting the invitation', function () {
    actingAs($this->stranger, $this->guard);

    get(inviteUrl('accept', $this->invitation))->assertForbidden();
    post(inviteUrl('accept', $this->invitation))->assertForbidden();

    assertDatabaseHas('team_invitations', ['id' => $this->invitation->id]);
    assertDatabaseMissing('team_user', ['team_id' => $this->team->id, 'user_id' => $this->stranger->id]);
});

it('rejects unsigned and tampered invitation urls', function () {
    actingAs($this->invitee, $this->guard);

    get(route('team-invitations.accept', ['invitation' => $this->invitation->id]))->assertForbidden();
    post(route('team-invitations.accept.confirm', ['invitation' => $this->invitation->id]))->assertForbidden();
    get(route('team-invitations.cancel', ['invitation' => $this->invitation->id]))->assertForbidden();
    post(route('team-invitations.cancel.confirm', ['invitation' => $this->invitation->id]))->assertForbidden();

    $other = $this->team->teamInvitations()->create(['email' => 'other@example.test', 'role' => 'editor']);
    $tampered = str_replace("/{$this->invitation->id}/", "/{$other->id}/", inviteUrl('accept', $this->invitation));
    post($tampered)->assertForbidden();

    assertDatabaseHas('team_invitations', ['id' => $this->invitation->id]);
    assertDatabaseMissing('team_user', ['team_id' => $this->team->id, 'user_id' => $this->invitee->id]);
});

it('does not change anything on a GET request', function () {
    actingAs($this->invitee, $this->guard);

    get(inviteUrl('accept', $this->invitation))->assertOk();
    get(inviteUrl('cancel', $this->invitation))->assertOk();

    assertDatabaseHas('team_invitations', ['id' => $this->invitation->id]);
    assertDatabaseMissing('team_user', ['team_id' => $this->team->id, 'user_id' => $this->invitee->id]);
});

it('lets the invitee cancel the invitation', function () {
    actingAs($this->invitee, $this->guard);

    post(inviteUrl('cancel', $this->invitation))->assertRedirect();

    assertDatabaseMissing('team_invitations', ['id' => $this->invitation->id]);
});

it('lets the team owner cancel the invitation', function () {
    actingAs($this->owner, $this->guard);

    post(inviteUrl('cancel', $this->invitation))->assertRedirect();

    assertDatabaseMissing('team_invitations', ['id' => $this->invitation->id]);
});

it('rejects an unrelated user cancelling the invitation', function () {
    actingAs($this->stranger, $this->guard);

    get(inviteUrl('cancel', $this->invitation))->assertForbidden();
    post(inviteUrl('cancel', $this->invitation))->assertForbidden();

    assertDatabaseHas('team_invitations', ['id' => $this->invitation->id]);
});

it('requires authentication', function () {
    Route::get('/login', fn () => 'login')->name('login');

    post(inviteUrl('accept', $this->invitation))->assertRedirect();

    assertDatabaseHas('team_invitations', ['id' => $this->invitation->id]);
});

it('builds a signed accept url in the invitation mail', function () {
    $mail = (new TeamInvitation($this->invitation))->build();

    expect($mail->viewData['acceptUrl'])->toContain('signature=');
});
