<?php

namespace TomatoPHP\FilamentSaasPanel\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use TomatoPHP\FilamentSaasPanel\Support\InvitationAccess;

class TeamsController
{
    /**
     * Signed GET: only shows a confirmation page, it never changes any data.
     */
    public function showAccept(Request $request, $invitationId)
    {
        $invitation = $this->findInvitation($invitationId);

        if (! $invitation) {
            return $this->home();
        }

        abort_unless(InvitationAccess::isInvitee($this->user($request), $invitation), 403);

        return view('filament-saas-panel::teams.confirm-invitation', [
            'invitation' => $invitation,
            'action' => 'accept',
        ]);
    }

    public function showCancel(Request $request, $invitationId)
    {
        $invitation = $this->findInvitation($invitationId);

        if (! $invitation) {
            return $this->home();
        }

        $user = $this->user($request);

        abort_unless(
            InvitationAccess::isInvitee($user, $invitation) || InvitationAccess::canManage($user, $invitation),
            403
        );

        return view('filament-saas-panel::teams.confirm-invitation', [
            'invitation' => $invitation,
            'action' => 'cancel',
        ]);
    }

    public function accept(Request $request, $invitationId)
    {
        $invitation = $this->findInvitation($invitationId);

        if (! $invitation) {
            return $this->home();
        }

        $user = $this->user($request);

        abort_unless(InvitationAccess::isInvitee($user, $invitation), 403);

        $team = $invitation->team;

        if (! $team->users()->whereKey($user->getKey())->exists() && (string) $team->user_id !== (string) $user->getKey()) {
            AddingTeamMember::dispatch($team, $user);

            $team->users()->attach($user, ['role' => $invitation->role]);

            TeamMemberAdded::dispatch($team, $user);
        }

        $invitation->delete();

        return redirect()->to(url(Filament::getCurrentOrDefaultPanel()->getId().'/'.$team->id));
    }

    public function cancel(Request $request, $invitationId)
    {
        $invitation = $this->findInvitation($invitationId);

        if (! $invitation) {
            return $this->home();
        }

        $user = $this->user($request);

        abort_unless(
            InvitationAccess::isInvitee($user, $invitation) || InvitationAccess::canManage($user, $invitation),
            403
        );

        $invitation->delete();

        return $this->home();
    }

    protected function findInvitation($invitationId)
    {
        $model = Jetstream::teamInvitationModel();

        return $model::whereKey($invitationId)->first();
    }

    protected function user(Request $request)
    {
        return $request->user(config('filament-saas-panel.auth_guard'));
    }

    protected function home()
    {
        return redirect()->to(url(Filament::getCurrentOrDefaultPanel()->getId()));
    }
}
