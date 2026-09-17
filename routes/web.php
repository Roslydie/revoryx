
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('welcome');
});

Route::get('/service', function () {
    return view('welcome');
});

Route::get('/service/{slug}', function () {
    return view('welcome');
})->where('slug', '[a-z0-9-]+');

Route::get('/contact', function () {
    return view('welcome');
});

Route::get('/policy', function () {
    return view('welcome');
});

Route::get('/blog', function () {
    return view('welcome');
});

Route::get('/blog/{slug}', function () {
    return view('welcome');
})->where('slug', '[a-z0-9-]+');

Route::get('/project', function () {
    return view('welcome');
});

Route::get('/project/{slug}', function () {
    return view('welcome');
})->where('slug', '[a-z0-9-]+');

Route::get('/admin', function () {
    return view('admin');
});

Route::get('/admin/{any}', function () {
    return view('admin');
})->where('any', '.*');


