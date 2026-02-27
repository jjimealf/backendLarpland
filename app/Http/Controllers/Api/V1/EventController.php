<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreEventRequest;
use App\Http\Requests\V1\UpdateEventRequest;
use App\Http\Resources\V1\EventResource;
use App\Models\Roleplay_event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Roleplay_event::class);

        $query = Roleplay_event::query();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($from = $request->input('from')) {
            $query->where('fecha_inicio', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->where('fecha_fin', '<=', $to);
        }

        $events = $query->orderBy('fecha_inicio')->paginate($this->perPage($request))->withQueryString();

        return $this->paginated($events, EventResource::class, 'Events fetched.');
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();
        $event = new Roleplay_event($data);

        if ($request->hasFile('image')) {
            $event->image = $request->file('image')->store('public/img');
        }

        $event->save();

        return $this->success(EventResource::make($event), 'Event created.', 201);
    }

    public function show(Roleplay_event $event): JsonResponse
    {
        $this->authorize('view', $event);

        return $this->success(EventResource::make($event), 'Event fetched.');
    }

    public function update(UpdateEventRequest $request, Roleplay_event $event): JsonResponse
    {
        $data = $request->validated();
        $nextStart = $data['fecha_inicio'] ?? $event->fecha_inicio;
        $nextEnd = $data['fecha_fin'] ?? $event->fecha_fin;

        if ($nextStart && $nextEnd && strtotime((string) $nextEnd) < strtotime((string) $nextStart)) {
            return $this->error(
                'validation_error',
                'The end date must be after or equal to start date.',
                422,
                ['fecha_fin' => ['The fecha_fin must be after or equal to fecha_inicio.']]
            );
        }

        $event->fill($data);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::delete($event->image);
            }
            $event->image = $request->file('image')->store('public/img');
        }

        $event->save();

        return $this->success(EventResource::make($event->fresh()), 'Event updated.');
    }

    public function destroy(Roleplay_event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        if ($event->image) {
            Storage::delete($event->image);
        }
        $event->delete();

        return $this->noContent();
    }
}
