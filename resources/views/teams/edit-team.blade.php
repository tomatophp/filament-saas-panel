
<x-filament-panels::page>
    @if(auth(config('filament-saas-panel.auth_guard'))->user()->id === $team->{config('filament-saas-panel.team_id_column')})
        <form wire:submit="saveEditTeam">
            {{ $this->editTeamForm }}
        </form>
    @endif
    @if(filament()->getPlugin('filament-saas-panel')->teamInvitation && auth(config('filament-saas-panel.auth_guard'))->user()->id === $team->{config('filament-saas-panel.team_id_column')})
        <form wire:submit="sendInvitation">
            {{ $this->manageTeamMembersForm }}
        </form>


        @if ($team->teamInvitations->isNotEmpty())
            <x-filament::section
                :heading="__('Team Member Invitations')"
                :description="__('All of the people that are part of this team.')"
            >
                <!-- Team Member Invitations -->
                <div class="mt-10 sm:mt-0">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach ($team->teamInvitations as $invitation)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="fi-account-widget-main">{{ $invitation->email }}</div>

                                <div class="fi-account-widget-logout-form">
                                    {{ $this->getResendInvitationAction()(['invitation'=>$invitation->id]) }}
                                    {{ $this->getCancelTeamInvitationAction()(['invitation'=>$invitation->id]) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-filament::section>
        @endif
    @endif

    @if(filament()->getPlugin('filament-saas-panel')->showTeamMembers)
        @if ($team->users->isNotEmpty())
            <x-filament::section
                :heading="trans('filament-saas-panel::messages.teams.members.list.title')"
                :description="trans('filament-saas-panel::messages.teams.members.list.description')"
            >
                <!-- Team Member Invitations -->
                <div class="mt-10 sm:mt-0">
                    <div class="space-y-6">
                        @foreach ($team->users->sortBy('name') as $user)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="fi-sidebar-item-btn" style="margin-left: -10px;">
                                    <div class="fi-user-menu-trigger">
                                        <x-filament::avatar
                                            class="fi-size-lg fi-circular"
                                            :src="$user->getFilamentAvatarUrl()?: 'https://ui-avatars.com/api/?name='.$user->name.'&color=FFFFFF&background=020617'"
                                            :alt="$user->name"
                                            size="lg"
                                        />
                                    </div>
                                    <div class="fi-sidebar-item-label">
                                        <div class="font-meduim text-md">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <!-- Manage Team Member Role -->
                                    @if (auth(config('filament-saas-panel.auth_guard'))->user()->id === \Filament\Facades\Filament::getTenant()->{config('filament-saas-panel.team_id_column')} && Laravel\Jetstream\Jetstream::hasRoles() && $user->membership->role)
                                        {{ ($this->getManageRoleAction(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name))(['user' => $user->id, 'role'=>$user->membership->role]) }}
                                    @elseif (Laravel\Jetstream\Jetstream::hasRoles() && $user->membership->role)
                                        <div class="ms-2 text-sm text-gray-400">
                                            {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}
                                        </div>
                                    @endif

                                    <!-- Leave Team -->
                                    @if (auth(config('filament-saas-panel.auth_guard'))->user()->id === $user->id)
                                        {{ ($this->getLeavingTeamAction())(['user'=> $user->id]) }}

                                    <!-- Remove Team Member -->
                                    @elseif (auth(config('filament-saas-panel.auth_guard'))->user()->id === \Filament\Facades\Filament::getTenant()->account_id)
                                        {{ ($this->getRemoveMemberAction())(['user'=> $user->id]) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-filament::section>
        @endif
    @endif

    @if(filament()->getPlugin('filament-saas-panel')->deleteTeam && auth(config('filament-saas-panel.auth_guard'))->user()->id === $team->{config('filament-saas-panel.team_id_column')})
        <form wire:submit="deleteTeam">
            {{ $this->deleteTeamFrom }}
        </form>
    @endif

    <x-filament-actions::modals />
</x-filament-panels::page>
