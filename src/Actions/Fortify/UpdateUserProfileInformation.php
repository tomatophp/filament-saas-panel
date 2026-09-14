<?php

namespace TomatoPHP\FilamentSaasPanel\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(Model $user, array $input): void
    {
        $table = config('filament-saas-panel.user_table', 'users');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique($table)->ignore($user->getKey())],
            'phone' => ['nullable', 'max:255', Rule::unique($table)->ignore($user->getKey())],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        $attributes = [
            'name' => $input['name'],
            'email' => $input['email'],
        ];

        if (array_key_exists('phone', $input)) {
            $attributes['phone'] = $input['phone'];
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $user->forceFill([...$attributes, 'email_verified_at' => null])->save();

            $user->sendEmailVerificationNotification();

            return;
        }

        $user->forceFill($attributes)->save();
    }
}
