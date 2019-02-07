<?php

namespace Tests\Integration;

use App\Event;
use App\Venue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Tests\TestCase;

class VenuTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Venue::class, new Venue);
    }

    /** @test */
    public function a_venue_has_many_events()
    {
        $venue = factory(Venue::class)->create();

        $events = factory(Event::class, 3)->make(['venue_id' => null]);

        $venue->events()->saveMany($events);

        $this->assertEquals(3, $venue->events->count());
        $this->assertInstanceof(Collection::class, $venue->events);

        $events->each(function (Event $event) use ($venue) {
            $this->assertEquals($venue->id, $event->venue->id);
        });
    }
}
