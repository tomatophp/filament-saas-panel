<?php

namespace TomatoPHP\FilamentSaasPanel\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

class InvitationAccess
{
    /**
     * Only the person the invitation was sent to may accept it.
     */
    public static function isInvitee(?Authenticatable $user, $invitation): bool
    {
        if (! $user || empty($user->email) || empty($invitation->email)) {
            return false;
        }

        return mb_strtolower(trim((string) $user->email)) === mb_strtolower(trim((string) $invitation->email));
    }

    /**
     * The team owner, a team admin or anyone allowed to remove team members may manage invitations.
     */
    public static function canManage(?Authenticatable $user, $invitation): bool
    {
        if (! $user) {
            return false;
        }

        $team = $invitation->team;

        if (! $team) {
            return false;
        }

        if ((string) $team->user_id === (string) $user->getAuthIdentifier()) {
            return true;
        }

        if (method_exists($user, 'hasTeamRole') && $user->hasTeamRole($team, 'admin')) {
            return true;
        }

        return Gate::forUser($user)->check('removeTeamMember', $team);
    }
}
