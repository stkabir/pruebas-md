<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::view('categorias', 'categories')
//     ->middleware(['auth', 'verified'])
//     ->name('categories');
// Route::view('productos', 'products')
//     ->middleware(['auth', 'verified'])
//     ->name('products');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('categorias', 'categories')->name('categories');
    Volt::route('productos', 'products')->name('products');
});

require __DIR__.'/auth.php';
