<?php

use Illuminate\Support\Facades\Route;

// ---------------- Frontend ----------------
Route::name('frontend.')->group(function () {
    // Home page
    Route::get('/', function () {
        return view('frontend.home');
    })->name('home');

    // About page
    Route::get('/about', function () {
        return view('frontend.about');
    })->name('about');

    // Contact page
    Route::get('/contact', function () {
        return view('frontend.contact');
    })->name('contact');
});
