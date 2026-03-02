<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Support\Concerns\ResolvesGatewayUrl;
use App\Support\Handler\HandlerFailure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PenController extends Controller
{
    use HandlerFailure;
    use ResolvesGatewayUrl;

    // Add a new pen
    public function addPen(Request $request): JsonResponse|RedirectResponse
    {
        $status = $request->string('status')->trim()->toString();
        if (! in_array($status, ['available', 'occupied'], true)) {
            $status = 'available';
        }

        $notes = $request->string('notes')->trim()->toString();
        if ($notes === '') {
            $notes = 'No notes';
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/pen/add/'), [
                    'pen_name' => $request->string('pen_name')->trim()->toString(),
                    'capacity' => $request->integer('capacity'),
                    'status' => $status,
                    'notes' => $notes,
                    'date' => $request->date('date')?->toDateTimeString() ?? now()->toDateTimeString(),
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Pen service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to add pen. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Pen added successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }

    public function updatePen(Request $request, string $pen_code): JsonResponse|RedirectResponse
    {
        $status = $request->string('status')->trim()->toString();
        if (! in_array($status, ['available', 'occupied'], true)) {
            $status = 'available';
        }

        $notes = $request->string('notes')->trim()->toString();
        if ($notes === '') {
            $notes = 'No notes';
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->put($this->endpointUrl('/pen/update/'.rawurlencode(trim($pen_code)).'/'), [
                    'pen_name' => $request->string('pen_name')->trim()->toString(),
                    'capacity' => $request->integer('capacity'),
                    'status' => $status,
                    'notes' => $notes,
                    'date' => $request->date('date')?->toDateTimeString() ?? now()->toDateTimeString(),
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Pen service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to update pen. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Pen updated successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }

    // Get all pen
    public function getAllPen(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/pen/all/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Pen service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to retrieve pens. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Pens retrieved successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }

    // Delete a pen
    public function deletePen(Request $request, string $pen_code): JsonResponse|RedirectResponse
    {
        $normalizedPenCode = trim($pen_code);
        if ($normalizedPenCode === '') {
            return $this->handleApiFailure($request, 422, ['message' => 'Pen code is required.'], 'Failed to remove pen. Please try again.');
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->delete($this->endpointUrl('/pen/delete/'.rawurlencode($normalizedPenCode).'/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Pen service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to remove pen. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Pen removed successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }
}
