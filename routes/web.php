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
    
    $user = auth()->user();
    $activeVideos = \App\Models\Video::with('uploader')
        ->where('status', 'Published')
        ->orderByDesc('upload_date')
        ->orderByDesc('created_at')
        ->get();
    $myUploadsCount = \App\Models\Video::where('uploaded_by', $user->id)->count();
    return view('member.dashboard', compact('activeVideos', 'myUploadsCount'));
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

// Suspend/Activate user
Route::post('/admin/users/suspend', [\App\Http\Controllers\UserController::class, 'suspend'])->name('admin.users.suspend');

// Custom Auth
Route::post('/login', [\App\Http\Controllers\Auth\CustomAuthController::class, 'login'])->name('login.custom');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\CustomAuthController::class, 'forgotPassword'])->name('password.email.custom');

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

Route::post('/admin/subscription/payment', [App\Http\Controllers\AdminDashboardController::class, 'saveSubscriptionPayment'])->name('admin.subscription.payment');

Route::middleware(['auth', 'verified'])->group(function () {
    // Member Uploaded Videos page
    Route::get('/member/uploaded-videos', function () {
        $videos = \App\Models\Video::with(['uploader', 'country'])
            ->orderByDesc('upload_date')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('member.videos', compact('videos'));
    })->name('member.videos');

    // Member Test Videos page
    Route::get('/member/test-videos', [\App\Http\Controllers\VideoController::class, 'testVideos'])
        ->name('member.test-videos');
    // Existing routes...

// Contact Information Page
Route::get('/contact-info', [\App\Http\Controllers\ContactController::class, 'info'])->name('contact.info');

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
    Route::get('/videos/{video}', [\App\Http\Controllers\VideoController::class, 'show'])->name('videos.show');
    Route::get('/videos/{video}/edit', [\App\Http\Controllers\VideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{id}', [\App\Http\Controllers\VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{id}', [\App\Http\Controllers\VideoController::class, 'destroy'])->name('videos.destroy');
    Route::post('/videos/{video}/comment', [\App\Http\Controllers\VideoController::class, 'comment'])->name('videos.comment');
    Route::get('/news/create', [\App\Http\Controllers\NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [\App\Http\Controllers\NewsController::class, 'store'])->name('news.store');
});

// Password reset (uses Laravel's default auth scaffolding)
// Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Public news routes
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Protected news routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/news/create', [App\Http\Controllers\NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [App\Http\Controllers\NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [App\Http\Controllers\NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [App\Http\Controllers\NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}', [App\Http\Controllers\NewsController::class, 'destroy'])->name('news.destroy');
    });
});

Route::post('/contact/submit', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

require __DIR__.'/auth.php';
