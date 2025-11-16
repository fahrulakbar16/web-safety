<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Http\Request;

class GetEventsAction
{
    /**
     * Get paginated events with filters.
     */
    public function execute(Request $request): array
    {
        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);

        if ($status == "all") {
            $status = null;
        }

        $events = Event::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        // Get counts for status tabs
        $statusCounts = [
            'all' => Event::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->count(),
            'active' => Event::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->where('status', 'active')
            ->count(),
            'inactive' => Event::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->where('status', 'inactive')
            ->count(),
        ];

        return [
            'events' => $events,
            'statusCounts' => $statusCounts,
        ];
    }
}

