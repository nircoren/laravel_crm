<?php

namespace Database\Factories;

use App\Enums\CallType;
use App\Models\Call;
use Illuminate\Database\Eloquent\Factories\Factory;

class CallFactory extends Factory {

    public function definition(): array {
        return [
            'agent_id' => $this->faker->numberBetween(1, 100),
            'customer_id' => $this->faker->numberBetween(1, 100),
            'duration' => $this->faker->numberBetween(1, 3600),
            'type' => $this->faker->randomElement(CallType::values()),
            'notes' => $this->faker->sentence(),
        ];
    }
}
