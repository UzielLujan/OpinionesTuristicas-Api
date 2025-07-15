<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('Todo OK desde Laravel', 200);
});
