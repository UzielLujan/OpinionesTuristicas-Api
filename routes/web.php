<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('crud'); // 'crud' será el nombre de nuestro archivo de vista
});