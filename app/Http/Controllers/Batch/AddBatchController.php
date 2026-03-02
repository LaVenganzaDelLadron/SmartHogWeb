<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Batch\BatchRequest;
use App\Models\GrowthStage;
use App\Models\Pen;
use App\Models\PigBatch;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class AddBatchController extends Controller
{
    use ResolvesGatewayUrl;

}
