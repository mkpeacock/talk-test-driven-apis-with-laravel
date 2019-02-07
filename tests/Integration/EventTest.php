<?php

namespace Tests\Integration;

use App\Event;
use App\Talk;
use App\Venue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Event::class, new Event);
    }

    /** @test */
    public function an_event_belongs_to_a_venue()
    {
        $event = factory(Event::class)->make(['venue_id' => null]);

        $venue = factory(Venue::class)->create();

        $event->venue()->associate($venue);
        $event->save();

        $this->assertEquals($venue->id, $event->venue_id);
        $this->assertEquals($venue, $event->venue);
    }

    /** @test */
    public function an_event_has_many_talks()
    {
        $event = factory(Event::class)->create();

        $talks = factory(Talk::class, 3)->make(['event_id' => null]);

        $event->talks()->saveMany($talks);

        $this->assertEquals(3, $event->talks()->count());
        $this->assertInstanceof(Collection::class, $event->talks);

        $talks->each(function (Talk $talk) use ($event) {
            $this->assertEquals($event->id, $talk->event->id);
        });
    }
}
