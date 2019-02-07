<?php

namespace Tests\Feature\Api\OnePointZero;

use App\Event;
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

    /** @test */
    public function get_request_on_events_endpoint_contains_event_data(): void
    {
        $event = factory(Event::class)->create();
        $response = $this->json('GET', '/api/1.0/events');

        $response->assertJsonFragment([
            'name' => $event->name,
            'description' => $event->description,
        ]);
    }

    /** @test */
    public function get_request_on_events_endpoint_does_not_contain_event_id(): void
    {
        $event = factory(Event::class)->create();
        $response = $this->json('GET', '/api/1.0/events');

        $response->assertJsonMissing([
            'id' => $event->id,
        ]);
    }

    /** @test */
    public function get_request_on_events_endpoint_includes_formatted_dates(): void
    {
        $event = factory(Event::class)->create();
        $response = $this->json('GET', '/api/1.0/events');

        $response->assertJsonFragment([
            'starts_at' => $event->starts_at->toIso8601String(),
            'ends_at' => $event->ends_at->toIso8601String(),
        ]);
    }

    /** @test */
    public function get_request_on_events_endpoint_contains_venue_data_within_event(): void
    {
        $event = factory(Event::class)->create();
        $response = $this->json('GET', '/api/1.0/events');

        $response->assertJsonFragment([
            'venue' => [
                'name' => $event->venue->name,
            ]
        ]);
    }
}
