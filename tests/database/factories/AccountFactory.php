<?php

namespace TomatoPHP\FilamentSaasPanel\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Account;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        $email = $this->faker->unique()->safeEmail();

        return [
            'name' => $this->faker->name(),
            'type' => 'account',
            'address' => $this->faker->address(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'email' => $email,
            'username' => $email,
            'loginBy' => 'email',
            'otp_activated_at' => now(),
            'password' => bcrypt('password'), // password
            'is_active' => 1,
            'is_login' => 1,
        ];
    }
}
