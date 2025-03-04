<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $query = Event::query()
            ->whereNot('user_id', auth()->id())
            ->where('start_date', '>=', now());

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->input('name').'%');
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%'.$request->input('location').'%');
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('start_date', [
                $request->input('start_date'),
                $request->input('end_date'),
            ]);
        }

        $events = $query->paginate(18);

        return EventResource::collection($events);
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = auth()->user()->organizedEvents()->create($request->validated());

        if ($event) {
            return response()->json([
                'success' => true,
                'message' => 'Event has been created succefully',
                'data' => new EventResource($event),
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Event could not be created',
        ], 500);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new EventResource($event),
        ], 200);
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $event->update($request->validated());

        if ($event->wasChanged()) {
            return response()->json([
                'success' => true,
                'message' => 'Event has been updated successfully',
                'data' => new EventResource($event),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Event could not be updated',
        ], 500);
    }

    public function destroy(Event $event): JsonResponse
    {
        if ($event->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function myEvents(): JsonResource
    {
        $events = auth()->user()->organizedEvents;

        return EventResource::collection($events);
    }
}
