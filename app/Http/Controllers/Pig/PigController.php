<?php

namespace App\Http\Controllers\Pig;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PigController extends Controller
{
    public function addBatch(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Frontend-only batch accepted.',
            'batch_id' => 'BATCH-'.strtoupper(substr(sha1((string) now()), 0, 8)),
        ]);
    }

    public function addPen(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Frontend-only pen accepted.',
            'pen_code' => 'PEN-'.strtoupper(substr(sha1((string) now()), 0, 8)),
        ]);
    }

    public function getPens(): JsonResponse
    {
        return response()->json([]);
    }

    public function getBatches(): JsonResponse
    {
        return response()->json([]);
    }

    public function getTotalPigs(): JsonResponse
    {
        return response()->json(['total_pigs' => 0]);
    }
}
