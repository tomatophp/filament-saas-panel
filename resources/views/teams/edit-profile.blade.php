<x-filament-panels::page>
    <form wire:submit="updateProfile">
        {{ $this->editProfileForm }}
    </form>

    @if(filament()->getPlugin('filament-saas-panel')->editPassword)
        <form wire:submit="updatePassword">
            {{ $this->editPasswordForm }}
        </form>
    @endif

    @if(filament()->getPlugin('filament-saas-panel')->browserSessionManager)
        <form>
            {{ $this->browserSessionsForm }}
        </form>
    @endif

    @if(filament()->getPlugin('filament-saas-panel')->deleteAccount)
        <form>
            {{ $this->deleteAccountForm }}
        </form>
    @endif
</x-filament-panels::page>
