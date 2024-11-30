<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Call;
use App\Models\Agent;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CallsGridFeatureTest extends TestCase {
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
    public function test_list_calls_with_pagination() {
        Call::factory()->count(30)->create([
            'agent_id' => $this->agent->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->get(route('index'));

        $response->assertStatus(200);
        $response->assertViewHas('calls');
        $response->assertSee('Calls CRM');

        $calls = $response->original->getData()['calls'];
        $this->assertCount(15, $calls);
    }

    /** @test */
    public function test_filter_calls_by_agent() {
        $agent1 = $this->agent;
        $agent2 = Agent::factory()->create();

        Call::factory()->count(5)->create([
            'agent_id' => $agent1->id,
            'customer_id' => $this->customer->id,
        ]);

        Call::factory()->count(3)->create([
            'agent_id' => $agent2->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->get(route('index') . '?filters[agents][id]=' . $agent1->id);

        $response->assertStatus(200);
        $calls = $response->original->getData()['calls'];
        $this->assertCount(5, $calls);
    }

    /** @test */
    public function test_filter_calls_by_date_range() {
        $startDate = now()->subDays(10);
        $endDate = now();

        Call::factory()->create([
            'agent_id' => $this->agent->id,
            'customer_id' => $this->customer->id,
            'created_at' => $startDate->subDays(5),
        ]);

        Call::factory()->count(3)->create([
            'agent_id' => $this->agent->id,
            'customer_id' => $this->customer->id,
            'created_at' => $startDate->addDays(2),
        ]);

        $response = $this->get(route('index') . '?from_date=' . $startDate->format('Y-m-d') . '&to_date=' . $endDate->format('Y-m-d'));

        $response->assertStatus(200);

        $calls = $response->original->getData()['calls'];

        $this->assertCount(3, $calls);
    }

}
