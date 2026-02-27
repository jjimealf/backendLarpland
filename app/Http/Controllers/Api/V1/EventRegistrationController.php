<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreEventRegistrationRequest;
use App\Http\Requests\V1\UpdateEventRegistrationRequest;
use App\Http\Resources\V1\EventRegistrationResource;
use App\Models\Event_registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventRegistrationController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Event_registration::class);

        $query = Event_registration::query()->with(['user', 'event']);
        $user = $request->user();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        } elseif (($userId = $request->input('user_id')) !== null) {
            $query->where('user_id', (int) $userId);
        }

        if (($eventId = $request->input('event_id')) !== null) {
            $query->where('event_id', (int) $eventId);
        }

        $registrations = $query->latest('id')->paginate($this->perPage($request))->withQueryString();

        return $this->paginated(
            $registrations,
            EventRegistrationResource::class,
            'Event registrations fetched.'
        );
    }

    public function store(StoreEventRegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if (!$user->isAdmin()) {
            $data['user_id'] = $user->id;
        } else {
            $data['user_id'] = $data['user_id'] ?? $user->id;
        }

        $duplicate = Event_registration::query()
            ->where('user_id', $data['user_id'])
            ->where('event_id', $data['event_id'])
            ->exists();

        if ($duplicate) {
            return $this->error(
                'duplicate_registration',
                'This user is already registered in this event.',
                422
            );
        }

        $registration = Event_registration::create($data);

        return $this->success(
            EventRegistrationResource::make($registration->load(['user', 'event'])),
            'Event registration created.',
            201
        );
    }

    public function show(Event_registration $eventRegistration): JsonResponse
    {
        $this->authorize('view', $eventRegistration);

        return $this->success(
            EventRegistrationResource::make($eventRegistration->load(['user', 'event'])),
            'Event registration fetched.'
        );
    }

    public function update(
        UpdateEventRegistrationRequest $request,
        Event_registration $eventRegistration
    ): JsonResponse {
        $data = $request->validated();

        if (!$request->user()->isAdmin()) {
            unset($data['user_id']);
        }

        $nextUserId = $data['user_id'] ?? $eventRegistration->user_id;
        $nextEventId = $data['event_id'] ?? $eventRegistration->event_id;

        $duplicate = Event_registration::query()
            ->where('id', '!=', $eventRegistration->id)
            ->where('user_id', $nextUserId)
            ->where('event_id', $nextEventId)
            ->exists();

        if ($duplicate) {
            return $this->error(
                'duplicate_registration',
                'This user is already registered in this event.',
                422
            );
        }

        $eventRegistration->update($data);

        return $this->success(
            EventRegistrationResource::make($eventRegistration->fresh()->load(['user', 'event'])),
            'Event registration updated.'
        );
    }

    public function destroy(Event_registration $eventRegistration): JsonResponse
    {
        $this->authorize('delete', $eventRegistration);

        $eventRegistration->delete();

        return $this->noContent();
    }
}
