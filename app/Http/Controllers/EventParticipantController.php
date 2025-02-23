<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EventParticipantStatus;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\JsonResponse;

class EventParticipantController extends Controller
{
    public function joinEvent($eventId): JsonResponse
    {
        $user = auth()->user();
        $event = Event::find($eventId);

        if (! $event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        if ($event->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Organizer cannot join as a participant.',
            ], 403);
        }

        // check if user already joind the event
        $existingParticipant = EventParticipant::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if ($existingParticipant) {
            $message = $existingParticipant->status === EventParticipantStatus::PENDING->value
                ? 'Join request already sent and awaiting approval.'
                : 'You are already a participant in this event.';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 409);
        }

        $status = $event->is_private
            ? EventParticipantStatus::PENDING->value
            : EventParticipantStatus::ACCEPTED->value;

        EventParticipant::create([
            'user_id' => $user->id,
            'event_id' => $eventId,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => $event->is_private
                ? 'Join request sent and awaiting approval.'
                : 'You have successfully joined the event.',
        ], 201);
    }
}
