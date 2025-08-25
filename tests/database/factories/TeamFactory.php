<?php

namespace TomatoPHP\FilamentSaasPanel\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Team;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'name' => $this->faker->name(),
            'personal_team' => false,
            'user_id' => $user->id,
        ];
    }
}
