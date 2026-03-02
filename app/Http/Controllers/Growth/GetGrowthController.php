<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Support\Concerns\ResolvesGatewayUrl;
use App\Support\Handler\HandlerFailure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class GetGrowthController extends Controller
{
    use HandlerFailure;
    use ResolvesGatewayUrl;

    public function getAllGrowthStage(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/growth/all/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Growth service is currently unavailable. Please try again.');
        } catch (Throwable) {
            return $this->handleGatewayFailure($request, 'An unexpected error occurred while fetching growth stages. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to fetch growth stages. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Growth stages retrieved successfully');
        $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
