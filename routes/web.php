<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'PHP' => phpversion(),
    ];
});

require __DIR__.'/api.php';