<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IotDataController;

// Endpoint ini bisa diakses di: http://localhost:8000/api/simpan-data-iot
// Method: POST
Route::post('/simpan-data-iot', [IotDataController::class, 'receiveData']);