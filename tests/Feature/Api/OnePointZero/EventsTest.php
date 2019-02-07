<?php

namespace Tests\Feature\Api\OnePointZero;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        $this->faker = Faker::create();

        parent::setUp();
    }

    /** @test */
    public function get_request_on_events_endpoint_returns_200_status_code(): void
    {
        $response = $this->json('GET', '/api/1.0/events');
        $response->assertStatus(200);
    }

    /** @test */
    public function get_request_on_events_endpoint_returns_json(): void
    {
        $response = $this->json('GET', '/api/1.0/events');
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([]);
    }
}
