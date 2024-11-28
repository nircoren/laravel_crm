<?php

namespace Database\Factories;

use App\Enums\CallType;
use App\Models\Call;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Call>
 */
class CallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Call::AGENT_RELATION_KEY => $this->faker->numberBetween(1, 100),
            Call::CUSTOMER_RELATION_KEY => $this->faker->numberBetween(1, 100),
            Call::DURATION => $this->faker->numberBetween(1, 3600),
            Call::TYPE => $this->faker->randomElement(CallType::values()),
        ];
    }
}
