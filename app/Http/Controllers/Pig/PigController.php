<?php

namespace App\Http\Controllers\Pig;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Models\PigBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PigController extends Controller
{
    public function addBatch(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255|unique:pig_batches,batch_name',
            'no_of_pigs' => 'required|integer|min:1',
            'current_age_days' => 'required|integer|min:0',
            'avg_weight_kg' => 'required|numeric|min:0',
            'growth_stage' => 'required|string|exists:growth_stages,growth_name',
            'notes' => 'nullable|string',
            'pen_id' => 'required|string|exists:pens,pen_code',
        ]);

        $selectedPen = Pen::query()
            ->where('pen_code', $validated['pen_id'])
            ->first();

        $currentPenLoad = PigBatch::query()
            ->where('pen_id', $validated['pen_id'])
            ->sum('no_of_pigs');

        $projectedPenLoad = $currentPenLoad + (int) $validated['no_of_pigs'];
        $penCapacity = (int) ($selectedPen?->capacity ?? 0);

        if ($projectedPenLoad > $penCapacity) {
            throw ValidationException::withMessages([
                'pen_id' => sprintf(
                    'Pen capacity exceeded. %s capacity is %d pigs. Current load is %d, adding %d would make it %d.',
                    $validated['pen_id'],
                    $penCapacity,
                    $currentPenLoad,
                    (int) $validated['no_of_pigs'],
                    $projectedPenLoad
                ),
            ]);
        }

        $batch = PigBatch::query()->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Batch added successfully.',
                'batch_id' => $batch->batch_id,
            ]);
        }

        return redirect()
            ->route('show.pig')
            ->with('success', 'Batch added successfully.');
    }

    public function addBatchFromWeb(Request $request): RedirectResponse
    {
        $request->merge([
            'no_of_pigs' => $request->input('pig_count'),
            'avg_weight_kg' => $request->input('avg_weight'),
            'pen_id' => $request->input('assigned_pen'),
            'notes' => $request->input('health_notes', $request->input('notes')),
        ]);

        $this->addBatch($request);

        return redirect()
            ->route('show.pig')
            ->with('success', 'Batch added successfully.');
    }

    public function addPen(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'pen_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'nullable|string|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        $pen = Pen::query()->create([
            'pen_name' => $validated['pen_name'],
            'capacity' => $validated['capacity'],
            'status' => $validated['status'] ?? 'available',
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Pen added successfully.',
                'pen_code' => $pen->pen_code,
            ]);
        }

        return redirect()
            ->route('show.pig')
            ->with('success', 'Pen added successfully.');
    }

    public function addPenFromWeb(Request $request): RedirectResponse
    {
        $this->addPen($request);

        return redirect()
            ->route('show.pig')
            ->with('success', 'Pen added successfully.');
    }

    public function getPens(): JsonResponse
    {
        $pens = Pen::query()->orderBy('pen_name')->get();

        return response()->json([
            'ok' => true,
            'data' => $pens,
        ]);
    }

    public function getBatches(): JsonResponse
    {
        $batches = PigBatch::query()->orderByDesc('record_date')->get();

        return response()->json([
            'ok' => true,
            'data' => $batches,
        ]);
    }

    public function getTotalPigs(): JsonResponse
    {
        $totalPigs = (int) PigBatch::query()->sum('no_of_pigs');

        return response()->json([
            'ok' => true,
            'total_pigs' => $totalPigs,
        ]);
    }
}
