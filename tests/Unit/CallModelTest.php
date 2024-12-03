<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Call;
use App\Models\Agent;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CallModelTest extends TestCase {
    use RefreshDatabase;
    private Agent $agent;
    private Customer $customer;

    protected function setUp(): void {
        parent::setUp();

        $this->agent = Agent::factory()->create();
        $this->customer = Customer::factory()->create(
            ['agent_id' => $this->agent->id]
        );
    }
    /** @test */
    public function test_related() {
        $call = Call::factory()->create(
            ['agent_id' => $this->agent->id, 'customer_id' => $this->customer->id]
        );
        $this->assertInstanceOf(Agent::class, $call->agent);
        $this->assertInstanceOf(Customer::class, $call->customer);
    }
}
