<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $array = [1, 2, 3, 4, 5];

    return view('welcome', [
        'array' => $array
    ]);
});

Route::get('/produtos', function () {

    $search = request('search');

    return view('products', [
        'search' => $search
    ]);
});

Route::get('/produtos/{id}', function ($id) {
    return view('product', ['id' => $id]);
});
