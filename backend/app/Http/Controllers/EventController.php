<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start' => 'required|date',
        'end' => 'required|date',
        'status' => 'nullable|string',
    ]);

    // Convert the datetime strings to the correct format
    $start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
    $end = Carbon::parse($request->end)->format('Y-m-d H:i:s');

    // Create the event using the formatted datetime
    $event = Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'start' => $start,
        'end' => $end,
        'status' => $request->status,
    ]);

    // Return a response
    return response()->json($event, 201);
}

    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event Not Found'], 404);
        }
        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event Not Found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->status = $request->input('status', 'Scheduled');
        $event->save();

        return response()->json(['message' => 'Event Updated Successfully', 'event' => $event]);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event Not Found'], 404);
        }

        $event->delete();
        return response()->json(['message' => 'Event Deleted Successfully']);
    }
}