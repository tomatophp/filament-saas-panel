<?php

namespace TomatoPHP\FilamentSaasPanel\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Account;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Team;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $account = Account::factory()->create();

        return [
            'name' => $this->faker->name(),
            'personal_team' => false,
            'account_id' => $account->id,
        ];
    }
}
