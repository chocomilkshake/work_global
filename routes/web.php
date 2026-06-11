<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPosting;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmployerAuthController;

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

Route::get('/jobs', [JobController::class, 'publicIndex']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/employer_register', function () {
    return view('employer_register');
});

Route::get('/get-started', function () {
    return view('get_started');
})->name('get-started');

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/employer_login', [EmployerAuthController::class, 'showLoginForm'])
    ->name('employer.login')
    ->middleware('guest:employer');
Route::post('/employer_login', [EmployerAuthController::class, 'login'])
    ->name('employer.login.post');

Route::get('/employer', function () {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    return view('employer.dashboard');
})->name('employer.dashboard');

Route::get('/employer/job', function () {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    return app(JobController::class)->index();
})->name('employer.job');

Route::get('/employer/add_job', function () {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    return app(JobController::class)->create();
})->name('employer.add_job');

Route::post('/employer/add_job', function (Request $request) {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    return app(JobController::class)->store($request);
})->name('employer.add_job.store');

Route::delete('/employer/job/{job}', function (JobPosting $job) {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    return app(JobController::class)->destroy($job);
})->name('employer.job.destroy');

Route::get('/admin/user', function () {
    return view('admin.user');
});

Route::get('/admin/employer', function () {
    return view('admin.employer');
});

Route::get('/admin/activity-logs', function () {
    return view('admin.activity-logs');
});

Route::get('/admin/employers', [EmployerController::class, 'index']);
Route::post('/admin/employer/{id}/approve', [EmployerController::class, 'approve']);
Route::post('/admin/employer/{id}/document/reject', [EmployerController::class, 'rejectDocument']);
Route::post('/admin/employer/{id}/reject', [EmployerController::class, 'reject']);
Route::post('/employer/register', [EmployerController::class, 'register']);

/* =========================
   LOGOUT
========================= */

Route::post('/logout', function () {
    $isEmployer = Auth::guard('employer')->check();

    if ($isEmployer) {
        Auth::guard('employer')->logout();
    }

    if (Auth::check()) {
        Auth::logout();
    }

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return $isEmployer
        ? redirect()->route('employer.login')
        : redirect('/login');
})->name('logout');