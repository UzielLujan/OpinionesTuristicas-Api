<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('crud');
});

Route::get('/debug/info', function () {
    phpinfo(); // Solo temporal
});
