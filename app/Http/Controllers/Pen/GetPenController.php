<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GetPenController extends Controller
{
    use ResolvesGatewayUrl;

    
}
