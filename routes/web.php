<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'PHP' => phpversion(),
        'Date/Time' => date('Y-m-d H:i:s'),
        'csrf_token' => csrf_token()
    ];
});

require __DIR__.'/api.php';