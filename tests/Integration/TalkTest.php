<?php

namespace Tests\Integration;

use App\Event;
use App\Talk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TalkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Talk::class, new Talk);
    }

    /** @test */
    public function a_talk_belongs_to_an_event()
    {
        $talk = factory(Talk::class)->make(['event_id' => null]);

        $event = factory(Event::class)->create();

        $talk->event()->associate($event);
        $talk->save();

        $this->assertEquals($event->id, $talk->event_id);
        $this->assertEquals($event, $talk->event);
    }
}
