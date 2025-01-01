<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebPageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/',"/files/");
Route::get("/files{path}",[WebPageController::class,"showFiles"])->where("path",".*");
