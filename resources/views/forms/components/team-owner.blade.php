@php
    $user = config('filament-saas-panel.user_model')::find($team->owner->id);
@endphp
<!-- Team Owner Information -->
<div class="col-span-6">
    <label>
        {{ trans('filament-saas-panel::messages.teams.edit.owner') }}
    </label>

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
</div>
