<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    use ApiResponse;

    protected function perPage(Request $request, int $default = 15): int
    {
        return max(1, min($request->integer('per_page', $default), 100));
    }
}
