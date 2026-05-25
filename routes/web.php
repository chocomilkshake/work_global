<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserAccountController;

Route::post('/user-account', [UserAccountController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jobs', function () {
    return view('jobs');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

//Admin Routes

Route::get('/admin/user', function () {
    return view('admin.user');
});

Route::get('/admin/activity-logs', function () {
    return view('admin.activity-logs');
});


// ✅ LOGOUT ROUTE
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');