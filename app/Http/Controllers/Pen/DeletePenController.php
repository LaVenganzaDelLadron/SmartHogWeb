<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Models\PigBatch;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeletePenController extends Controller
{
    use ResolvesGatewayUrl;

    
}
