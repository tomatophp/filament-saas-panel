<div class="fi-simple-page">
    @livewire('notifications')

    <section style="max-width: 400px; margin: 0 auto;">
        <x-filament-panels::header.simple
            :heading="$this->getHeading()"
            :logo="true"
            :subheading="$this->getSubHeading()"
        />

        <form wire:submit="authenticate" style="max-width: 400px; margin: 0 auto; padding: 20px 0 20px 0;">
            {{ $this->form }}

            <div
                style="display: flex; justify-content: center; align-items: center; margin-top: 20px;"
            >
                <x-filament::actions
                    :actions="$this->getCachedFormActions()"
                    alignment="center"
                />
            </div>
        </form>
    </section>
</div>
