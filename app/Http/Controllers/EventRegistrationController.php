<?php

namespace App\Http\Controllers;

use App\Models\Event_registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event_registration::all();
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'event_id' => 'required|integer|exists:roleplay_events,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = new Event_registration($request->only([
            'user_id',
            'event_id',
        ]));
        $event->save();
        return response()->json($event, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event_registration::findOrfail($id);
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|integer|exists:users,id',
            'event_id' => 'sometimes|integer|exists:roleplay_events,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = Event_registration::findOrFail($id);
        $event->update($request->only([
            'user_id',
            'event_id',
        ]));

        return response()->json($event, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event_registration::findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
