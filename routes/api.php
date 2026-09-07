<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobListingController;
use App\Http\Controllers\Api\ApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::apiResource('jobs', JobListingController::class)
->only(['index', 'show'])
->parameters(['jobs' => 'jobListing']);

Route::apiResource('jobs', JobListingController::class)
    ->middleware('auth:sanctum')
    ->except(['index', 'show'])
    ->parameters(['jobs' => 'jobListing']);

Route::apiResource('applications', ApplicationController::class)
    ->middleware('auth:sanctum')
    ->parameters(['applications' => 'application']);
