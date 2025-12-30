<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Izipay Webhook IPN (without CSRF protection)
Route::post('webhook/izipay/ipn', [WebhookController::class, 'handleIpnV4'])
    ->name('webhook.izipay.ipn');
