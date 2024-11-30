<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Call;
use App\Models\Agent;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CallModelTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function test_related() {
        $agent = Agent::factory()->create();
        $customer = Customer::factory()->create(
            ['agent_id' => $agent->id]);
        $call = Call::factory()->create(
            ['agent_id' => $agent->id, 'customer_id' => $customer->id]
        );
        $this->assertInstanceOf(Agent::class, $call->agent);
        $this->assertInstanceOf(Customer::class, $call->customer);
    }
}
