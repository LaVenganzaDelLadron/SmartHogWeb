<?php

namespace App\Http\Controllers\Pig;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Models\PigBatch;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PigController extends Controller
{
    // adding data
    public function addBatch(Request $request)
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255|unique:pig_batches,batch_name',
            'no_of_pigs' => 'required|integer|min:1',
            'avg_weight_kg' => 'required|numeric|min:0',
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

        $batch = PigBatch::create($validated);

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

    public function addPen(Request $request)
    {
        $validated = $request->validate([
            'pen_name' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'status' => 'string|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        Pen::create($validated);
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Pen added successfully.',
            ]);
        }

        return redirect()
            ->route('show.pig')
            ->with('success', 'Pen added successfully.');
    }

    // getting data
    public function getPens()
    {
        $pens = Pen::all();
        return response()->json($pens);
    }

    public function getBatches()
    {
        $batches = PigBatch::all();
        return response()->json($batches);
    }

    public function getTotalPigs()
    {
        $totalPigs = PigBatch::sum('no_of_pigs');
        return response()->json(['total_pigs' => $totalPigs]);
    }


}
