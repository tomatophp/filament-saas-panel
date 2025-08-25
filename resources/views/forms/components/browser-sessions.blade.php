<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="">
            <div class="mt-4 text-sm text-gray-600">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ trans('filament-saas-panel::messages.profile.browser.sessions_content') }}
                </div>
                @if (count($data) > 0)
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach ($data as $session)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    @if ($session->device['desktop'])
                                        <x-filament::icon
                                            icon="heroicon-o-computer-desktop"
                                            class="fi-size-lg fi-circular"
                                        />
                                    @else
                                        <x-filament::icon
                                            icon="heroicon-o-device-phone-mobile"
                                            class="fi-size-lg fi-circular"
                                        />
                                    @endif
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $session->device['platform'] ? $session->device['platform'] : __('Unknown') }} - {{ $session->device['browser'] ? $session->device['browser'] : __('Unknown') }}
                                    </div>

                                    <div>
                                        <div style="font-size: 12px; color: #6b7280;">
                                            {{ $session->ip_address }},

                                            @if ($session->is_current_device)
                                                <span class="font-semibold text-primary-500">{{ trans('filament-saas-panel::messages.profile.browser.sessions_device') }}</span>
                                            @else
                                                {{ trans('filament-saas-panel::messages.profile.browser.sessions_last_active') }} {{ $session->last_active }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-dynamic-component>
