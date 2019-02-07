<?php

namespace App\Http\Controllers\Api;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{

    public function index()
    {
        return \App\Http\Resources\Event::collection(
            Event::all()
        );
    }
    
    public function store(Request $request)
    {
        $event = new Event();
        $event->name = $request->name;
        $event->slug = $request->slug;
        $event->description = $request->description;
        $event->starts_at = new Carbon($request->starts_at);
        $event->ends_at = new Carbon($request->ends_at);
        $event->venue_id = $request->venue_id;
        $event->save();

        return (new \App\Http\Resources\Event($event))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
