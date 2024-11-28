<?php

// tests/Unit/CustomerTest.php

use App\Models\Customer;
use App\Models\Call;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has many calls', function () {

    $agent = \App\Models\Agent::create([
        'name' => 'Agent Name',
        'email' => 'agent@example.com',
        'phone' => '+1-202-555-0170',
    ]);

    // Create a customer using that agent
    $customer = \App\Models\Customer::create([
        'name' => 'Sarina Lemke',
        'email' => 'wschowalter@example.net',
        'phone' => '+1-715-742-9310',
        'address' => '993 Wolff Mission',
        'agent_id' => $agent->id,  // Valid agent_id
    ]);

    $customer = Customer::factory()->create(
        ['agent_id' => 1]
    );
    $calls = Call::factory()->count(3)->create(['customer_id' => $customer->id]);

    expect($customer->calls)->toHaveCount(3);
});
