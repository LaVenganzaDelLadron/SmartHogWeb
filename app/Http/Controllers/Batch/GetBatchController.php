<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Models\GrowthStage;
use App\Models\PigBatch;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class GetBatchController extends Controller
{
    use ResolvesGatewayUrl;

    
}
