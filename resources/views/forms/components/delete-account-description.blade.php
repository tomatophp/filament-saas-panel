<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="text-left">
            <div class="mt-4 text-sm text-gray-600">
                {{ trans('filament-saas-panel::messages.profile.delete.delete_account_card_description') }}
            </div>
        </div>
    </div>
</x-dynamic-component>
