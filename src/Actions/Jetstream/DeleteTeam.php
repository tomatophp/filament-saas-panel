<?php

namespace TomatoPHP\FilamentSaasPanel\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesTeams;
use TomatoPHP\FilamentSaasPanel\Models\Team;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     */
    public function delete(Team $team): void
    {
        $team->purge();
    }
}
