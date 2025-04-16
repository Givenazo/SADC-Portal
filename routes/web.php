<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    
    if (auth()->user()->role && auth()->user()->role->name === 'Admin') {
        return redirect('/admin/dashboard');
    }
    
    $activeVideos = \App\Models\Video::with('uploader')
        ->where('status', 'Published')
        ->orderByDesc('upload_date')
        ->get();

    return view('member.dashboard', compact('activeVideos'));
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::get('/admin/videos', [\App\Http\Controllers\AdminVideoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.videos.index');

// TEMPORARY DEBUG ROUTE: Only 'auth' middleware
Route::get('/admin/debug-dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth']);

Route::get('/admin/countries', [\App\Http\Controllers\AdminCountryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.countries');

// TEMPORARY DEBUG ROUTE: View authenticated user and their role info
Route::get('/debug-user-role', function () {
    $user = auth()->user();
    return [
        'user' => $user,
        'role' => $user?->role,
        'role_name' => $user?->role?->name,
    ];
})->middleware(['auth']);

// Admin User Management
Route::middleware(['auth', 'verified'])->prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\UserController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Change password
    Route::get('/password/change', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/password/change', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'change'])->name('password.change');

    // Member video and news upload routes
    Route::get('/videos/create', [\App\Http\Controllers\VideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [\App\Http\Controllers\VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos', [\App\Http\Controllers\VideoController::class, 'index'])->name('videos.index');
    Route::get('/news/create', [\App\Http\Controllers\NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [\App\Http\Controllers\NewsController::class, 'store'])->name('news.store');
    Route::delete('/videos/{id}', [\App\Http\Controllers\VideoController::class, 'destroy'])->name('videos.destroy');
});

// Password reset (uses Laravel's default auth scaffolding)
// Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// News CRUD routes
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index'); // Public homepage
Route::get('/news/{news}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

Route::middleware(['auth', 'verified', 'can:isAdmin'])->group(function () {
    Route::get('/admin/news/create', [\App\Http\Controllers\NewsController::class, 'create'])->name('news.create');
    Route::post('/admin/news', [\App\Http\Controllers\NewsController::class, 'store'])->name('news.store');
    Route::get('/admin/news/{news}/edit', [\App\Http\Controllers\NewsController::class, 'edit'])->name('news.edit');
    Route::put('/admin/news/{news}', [\App\Http\Controllers\NewsController::class, 'update'])->name('news.update');
    Route::delete('/admin/news/{news}', [\App\Http\Controllers\NewsController::class, 'destroy'])->name('news.destroy');
});

require __DIR__.'/auth.php';
