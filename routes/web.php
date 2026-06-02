<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserAccountController;

/* =========================
   USER ACCOUNT ROUTES
========================= */

Route::get('/user-account', [UserAccountController::class, 'index']);
Route::get('/user-account/trash', [UserAccountController::class, 'trash']);

Route::post('/user-account', [UserAccountController::class, 'store']);

Route::delete('/user-account/{id}', [UserAccountController::class, 'destroy']);

Route::post('/user-account/{id}/restore', [UserAccountController::class, 'restore']);

Route::delete('/user-account/{id}/force', [UserAccountController::class, 'forceDelete']);

/* =========================
   PAGES
========================= */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jobs', function () {
    return view('jobs');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/employer_login', function () {
    return view('employer_login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/employer_register', function () {
    return view('employer_register');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin/user', function () {
    return view('admin.user');
});

Route::get('/admin/employer', function () {
    return view('admin.employer');
});

Route::get('/admin/activity-logs', function () {
    return view('admin.activity-logs');
});

use App\Http\Controllers\EmployerController;

Route::get('/admin/employers', [EmployerController::class, 'index']);
Route::post('/employer/register', [EmployerController::class, 'register']);

/* =========================
   LOGOUT
========================= */

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');