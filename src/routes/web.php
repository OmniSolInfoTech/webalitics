<?php

use Osit\Webalitics\Controllers\WebaliticController;
use Illuminate\Support\Facades\Route;

Route::get('/webalitics/admin', [WebaliticController::class, "adminWebalitic"]);

Route::post('/webalitics/webalitic-profile', [WebaliticController::class, "adminWebaliticProfile"])->name('/webalitics/webalitic-profile');