<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $query = Event::query();

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

        $events = $query->paginate(20);

        return EventResource::collection($events);
    }
}
