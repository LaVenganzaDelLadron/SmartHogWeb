<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Growth\GrowthRequest;
use App\Models\GrowthStage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddGrowthController extends Controller
{
    // Adding a Growth Stage
    public function addGrowthStage(GrowthRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $growthCode = isset($validated['growth_code']) && is_string($validated['growth_code']) && trim($validated['growth_code']) !== ''
            ? trim($validated['growth_code'])
            : $this->generateNextGrowthCode();
        $recordDate = isset($validated['date'])
            ? Carbon::parse($validated['date'])->toIso8601String()
            : now()->toIso8601String();

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/growth/add/'), [
                    'growth_code' => $growthCode,
                    'growth_name' => $validated['growth_name'],
                    'date' => $recordDate,
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Growth service is currently unavailable. Please try again.');
        }
        if (! $response->successful()) {
            return $this->handleGatewayFailure(
                $request,
                $this->extractMessage($response->json(), 'Failed to create growth stage.'),
                $response->status()
            );
        }
        $payload = $response->json();
        $returnedGrowthCode = (string) ($payload['growth_code'] ?? $growthCode);
        $growthName = (string) ($payload['growth_name'] ?? $validated['growth_name']);

        if ($this->hasGrowthCodeColumn()) {
            if ($returnedGrowthCode !== '') {
                GrowthStage::query()->updateOrCreate(
                    ['growth_code' => $returnedGrowthCode],
                    ['growth_name' => $growthName]
                );
            }
        } else {
            GrowthStage::query()->firstOrCreate(
                ['growth_name' => $growthName]
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Growth stage saved successfully.',
                'growth_code' => $returnedGrowthCode,
            ]);
        }

        return redirect()
            ->route('show.pig')
            ->with('success', 'Growth stage saved successfully.');

    }

    public function addGrowthStageFromWeb(GrowthRequest $request): RedirectResponse
    {
        $response = $this->addGrowthStage($request);
        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return redirect()->route('show.pig');
    }

    private function endpointUrl(string $path): string
    {
        $baseUrl = rtrim((string) config('services.shapi_auth.base_url', 'http://shapi-qq0p.onrender.com'), '/');
        $normalizedPath = '/'.ltrim($path, '/');

        return $baseUrl.$normalizedPath;
    }

    private function generateNextGrowthCode(): string
    {
        if (! $this->hasGrowthCodeColumn()) {
            return 'GROWTH'.now()->format('ymdHis');
        }

        $codes = GrowthStage::query()
            ->whereNotNull('growth_code')
            ->pluck('growth_code');

        $max = 0;
        foreach ($codes as $code) {
            if (! is_string($code)) {
                continue;
            }
            if (preg_match('/^GROWTH-?(\d+)$/i', $code, $matches) === 1) {
                $max = max($max, (int) $matches[1]);
            }
        }

        return sprintf('GROWTH%03d', $max + 1);
    }

    private function hasGrowthCodeColumn(): bool
    {
        return Schema::hasColumn('growth_stages', 'growth_code');
    }

    private function extractMessage(mixed $payload, string $fallback): string
    {
        if (is_array($payload)) {
            $message = $payload['message'] ?? null;
            if (is_string($message) && $message !== '') {
                return $message;
            }

            $firstValue = reset($payload);
            if (is_array($firstValue)) {
                $firstItem = $firstValue[0] ?? null;
                if (is_string($firstItem) && $firstItem !== '') {
                    return $firstItem;
                }
            }

            if (is_string($firstValue) && $firstValue !== '') {
                return $firstValue;
            }
        }

        return $fallback;
    }

    private function handleGatewayFailure(Request $request, string $message, int $status = 503): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
            ], $status);
        }

        return back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withErrors(['growth' => $message]);
    }
}
