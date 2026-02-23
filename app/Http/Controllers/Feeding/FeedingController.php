<?php

namespace App\Http\Controllers\Feeding;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeedingController extends Controller
{
    public function showFeedingManagement(): View
    {
        return view('feeding.index', [
            'feedingBatches' => collect(),
            'feedingTypes' => collect(),
            'feedingCards' => collect(),
        ]);
    }

    public function addSchedule(Request $request): JsonResponse|RedirectResponse
    {
        $feedingId = 'FEED-'.strtoupper(substr(sha1((string) now()), 0, 8));

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Frontend-only schedule accepted.',
                'feeding_id' => $feedingId,
            ]);
        }

        return redirect()
            ->route('show.feeding')
            ->with('success', 'Frontend-only schedule accepted.');
    }

    public function listSchedules(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Frontend-only schedules list.',
            'data' => [],
        ]);
    }
}
