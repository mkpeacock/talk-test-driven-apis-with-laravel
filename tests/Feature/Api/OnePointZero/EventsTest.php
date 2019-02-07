<?php

namespace Tests\Feature\Api\OnePointZero;

use App\Event;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
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

    /** @test */
    public function post_request_on_events_endpoint_with_valid_data_creates_an_event(): void
    {
        $event = factory(Event::class)->make();
        $eventRequestData = $this->buildCreateRequestDataFromEvent($event);

        $response = $this->json(
            'POST',
            '/api/1.0/events',
            $eventRequestData
        );

        $lastCreatedEvent = Event::orderBy('id', 'desc')->firstOrFail();
        $this->assertEquals($event->name, $lastCreatedEvent->name);
        $this->assertEquals($event->slug, $lastCreatedEvent->slug);
    }

    /** @test */
    public function post_request_on_events_endpoint_with_valid_data_returns_201_response(): void
    {
        $event = factory(Event::class)->make();
        $eventRequestData = $this->buildCreateRequestDataFromEvent($event);

        $response = $this->json(
            'POST',
            '/api/1.0/events',
            $eventRequestData
        );

        $response->assertStatus(201);
    }

    /** @test */
    public function post_request_on_events_endpoint_with_valid_data_returns_event_data_in_response(): void
    {
        $event = factory(Event::class)->make();
        $eventRequestData = $this->buildCreateRequestDataFromEvent($event);

        $response = $this->json(
            'POST',
            '/api/1.0/events',
            $eventRequestData
        );

        $response->assertJsonFragment(
            (new \App\Http\Resources\Event($event))->toArray(new Request())
        );
    }

    /**
     * @test
     * @dataProvider requiredFields
     */
    public function post_request_on_events_endpoint_with_required_field_missing_returns_422_response($field): void
    {
        $event = factory(Event::class)->make();
        $eventRequestData = $this->buildCreateRequestDataFromEvent($event);
        unset($eventRequestData[$field]);

        $response = $this->json(
            'POST',
            '/api/1.0/events',
            $eventRequestData
        );

        $response->assertStatus(422);
    }

    public function requiredFields(): array
    {
        return [
            'name' => ['name'],
           'slug' =>  ['slug'],
            'venue id' => ['venue_id'],
            'description' => ['description'],
            'starts at' => ['starts_at'],
            'ends at' => ['ends_at'],
        ];
    }

    protected function buildCreateRequestDataFromEvent(Event $event): array
    {
        return [
            'name' => $event->name,
            'slug' => $event->slug,
            'description' => $event->description,
            'venue_id' => $event->venue_id,
            'starts_at' => $event->starts_at->format('Y-m-d H:i:s'),
            'ends_at' => $event->ends_at->format('Y-m-d H:i:s')
        ];
    }
}
