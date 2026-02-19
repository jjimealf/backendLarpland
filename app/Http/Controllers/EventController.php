<?php

namespace App\Http\Controllers;

use App\Models\Roleplay_event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Roleplay_event::all();
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'image' => 'sometimes|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = new Roleplay_event($request->only([
            'nombre',
            'descripcion',
            'fecha_inicio',
            'fecha_fin',
        ]));

        if ($request->hasFile('image')) {
            $event->image = $request->file('image')->store('public/img');
        }

        $event->save();
        return response()->json($event, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Roleplay_event::findOrfail($id);
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|string',
            'descripcion' => 'sometimes|string',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'sometimes|date',
            'image' => 'sometimes|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = Roleplay_event::findOrfail($id);

        $event->fill($request->only([
            'nombre',
            'descripcion',
            'fecha_inicio',
            'fecha_fin',
        ]));

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::delete($event->image);
            }
            $event->image = $request->file('image')->store('public/img');
        }

        $event->save();
        return response()->json($event, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Roleplay_event::findOrfail($id);
        $event->delete();
        return response()->json(null, 204);
    }
}
