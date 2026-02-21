<?php

namespace App\Http\Controllers\Pig;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Models\PigBatch;
use Illuminate\Http\Request;

class PigController extends Controller
{
    public function addBatch(Request $request)
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255|unique:pig_batches,batch_name',
            'no_of_pigs' => 'required|integer|min:1',
            'avg_weight_kg' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'pen_id' => 'required|string|exists:pens,pen_code',
        ]);

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

    public function getPens()
    {
        $pens = Pen::all();

        return response()->json($pens);
    }
}
