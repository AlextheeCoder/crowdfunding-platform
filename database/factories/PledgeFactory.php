<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pledge>
 */
class PledgeFactory extends Factory
{
    protected $model = \App\Models\Pledge::class;

    public function definition()
    {
        return [
            'user_id' => function() {
                return \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory()->create()->id;
            },
            'campaign_id' => function() {
                return \App\Models\Campaign::inRandomOrder()->first()->id ?? \App\Models\Campaign::factory()->create()->id;
            },
            'amount' => $this->faker->randomFloat(2, 1, 500),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'), 
        ];
    }
}
