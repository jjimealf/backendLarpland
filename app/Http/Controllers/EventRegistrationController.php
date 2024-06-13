<?php

namespace App\Http\Controllers;

use App\Models\Event_registration;
use Illuminate\Http\Request;

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
        $event = new Event_registration($request->all());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
