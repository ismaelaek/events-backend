<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class EventParticipantController extends Controller
{
    public function joinEvent(int $eventId): JsonResponse
    {
        $user = auth()->user();
        $event = Event::find($eventId);

        if (! $event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        if ($event->max_participants && $event->participants()->where('status', 'accepted')->count() === $event->max_participants) {
            return response()->json([
                'success' => false,
                'message' => 'Event is full',
            ], 403);
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
            $message = $existingParticipant->status === 'pending'
                ? 'Join request already sent and awaiting approval.'
                : 'You are already a participant in this event.';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 409);
        }

        $status = $event->is_private
            ? 'pending'
            : 'accepted';

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

    public function leaveEvent(int $eventId): JsonResponse
    {
        $user = auth()->user();
        $eventParticipant = EventParticipant::where('user_id', $user->id)->where('event_id', $eventId)->first();

        if (! $eventParticipant) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a participant in this event.',
            ], 404);
        }

        if ($eventParticipant->status === 'pending') {
            $eventParticipant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Join request has been canceled.',
            ], 200);
        }

        $eventParticipant->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have successfully left the event.',
        ], 200);
    }

    public function acceptJoinRequest(Event $event, User $user): JsonResponse
    {
        return $this->changeParticipantStatus($event, $user, 'accepted');
    }

    public function rejectJoinRequest(Event $event, User $user): JsonResponse
    {
        return $this->changeParticipantStatus($event, $user, 'rejected');
    }

    private function changeParticipantStatus(Event $event, User $user, string $status): JsonResponse
    {
        if ($event->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($status === 'accepted' && $event->max_participants
            && $event->participants()->where('status', 'accepted')->count() === $event->max_participants) {
            return response()->json(['message' => 'Event is full'], 403);
        }

        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($participant->status === $status) {
            return response()->json(['message' => "Participant is already $status"], 409);
        }

        $participant->update(['status' => $status]);

        return response()->json(['message' => "Participant $status"]);
    }
}
