<?php

use App\Http\Resources\LegalCaseResource;
use App\Models\LegalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/lawyer-schedule/{id}', function ($id) {
    return new LegalCaseResource(LegalCase::find($id));
});
