<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Document;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/documents', function (Request $request) {
    return Document::where('statut', 'disponible')
        ->where('service', $request->service)
        ->get(['idDocument', 'titre']);
        
});

