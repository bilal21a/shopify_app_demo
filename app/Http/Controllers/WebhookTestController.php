<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookTestController extends Controller
{
    public function testWebhook(Request $request) {
        Log::info("api test");
        Log::info($request->all());
        Log::info($request->product_type);
        return "success" ;
    }
}
