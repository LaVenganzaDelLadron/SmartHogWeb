<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pen\PenRequest;
use App\Models\Pen;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class UpdatePenController extends Controller
{
    use ResolvesGatewayUrl;

}
