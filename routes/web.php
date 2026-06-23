<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPosting;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmployerAuthController;
use App\Http\Controllers\ApplicantController;

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

$employerPendingRedirect = function () {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    $employer = Auth::guard('employer')->user();

    if ($employer && $employer->status === 'Pending') {
        return redirect()->route('employer.dashboard')
            ->with('warning', 'Your account is pending approval. Only Dashboard and Account Settings are available.');
    }

    return null;
};

Route::get('/employer', function () {
    if (! Auth::guard('employer')->check()) {
        return redirect()->route('employer.login');
    }

    $employer = Auth::guard('employer')->user();
    $jobs = JobPosting::where('employer_id', $employer->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('employer.dashboard', compact('jobs'));
})->name('employer.dashboard');

Route::get('/employer/job', function () use ($employerPendingRedirect) {
    if ($redirect = $employerPendingRedirect()) {
        return $redirect;
    }

    return app(JobController::class)->index();
})->name('employer.job');

Route::get('/employer/add_job', function () use ($employerPendingRedirect) {
    if ($redirect = $employerPendingRedirect()) {
        return $redirect;
    }

    return app(JobController::class)->create();
})->name('employer.add_job');

Route::post('/employer/add_job', function (Request $request) use ($employerPendingRedirect) {
    if ($redirect = $employerPendingRedirect()) {
        return $redirect;
    }

    return app(JobController::class)->store($request);
})->name('employer.add_job.store');

Route::get('/employer/account', [EmployerController::class, 'accountSettings'])
    ->name('employer.account');

Route::post('/employer/account', [EmployerController::class, 'updateAccount'])
    ->name('employer.account.update');

Route::delete('/employer/job/{job}', function (JobPosting $job) use ($employerPendingRedirect) {
    if ($redirect = $employerPendingRedirect()) {
        return $redirect;
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


Route::post('/applicant/store', [ApplicantController::class, 'store']);
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