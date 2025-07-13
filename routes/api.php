<?php

use App\Http\Resources\LawyerResource;
use App\Models\Lawyer;
use Illuminate\Support\Facades\Route;


Route::get('/lawyer-schedule/{id}', function ($id) {
    return new LawyerResource(Lawyer::find($id));
});
