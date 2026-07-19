<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Mahasiswa;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', fn() => auth()->check()
    ? redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'mahasiswa.dashboard')
    : redirect()->route('login')
);

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot Password
    Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password/send-otp', [\App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::post('/forgot-password/verify-otp', [\App\Http\Controllers\ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');
    
    // Reset Password
    Route::get('/reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Tools CRUD
    Route::resource('tools', Admin\ToolController::class)->except('show');
    Route::get('/tools/{tool}/mutasi', [Admin\ToolController::class, 'showMutasi'])->name('tools.mutasi');
    Route::post('/tools/{tool}/mutasi', [Admin\ToolController::class, 'storeMutasi'])->name('tools.storeMutasi');
    Route::post('/tools/{id}/restore', [Admin\ToolController::class, 'restore'])->name('tools.restore');

    // Borrowings management
    Route::get('/borrowings', [Admin\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [Admin\BorrowingController::class, 'show'])->name('borrowings.show');
    Route::post('/borrowings/{borrowing}/approve', [Admin\BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{borrowing}/reject', [Admin\BorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::post('/borrowings/{borrowing}/handover', [Admin\BorrowingController::class, 'handover'])->name('borrowings.handover');
    Route::get('/borrowings/{borrowing}/return', [Admin\BorrowingController::class, 'returnForm'])->name('borrowings.return');
    Route::post('/borrowings/{borrowing}/return', [Admin\BorrowingController::class, 'processReturn'])->name('borrowings.processReturn');

    // Items (Inventory) CRUD
    Route::resource('items', Admin\ItemController::class)->except('show');
    Route::get('/items/{item}/mutasi', [Admin\ItemController::class, 'showMutasi'])->name('items.mutasi');
    Route::post('/items/{item}/mutasi', [Admin\ItemController::class, 'storeMutasi'])->name('items.storeMutasi');
    Route::post('/items/{item}/restore', [Admin\ItemController::class, 'restore'])->name('items.restore');

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');

    // Audit Logs
    Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [Admin\AuditLogController::class, 'show'])->name('audit-logs.show');

    // User Management
    Route::get('/users', [Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle', [Admin\UserManagementController::class, 'toggleActive'])->name('users.toggle');
});

// Mahasiswa routes
Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa,dosen'])->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

    // Catalog
    Route::get('/catalog', [Mahasiswa\CatalogController::class, 'index'])->name('catalog.index');

    // Borrowings
    Route::get('/borrowings', [Mahasiswa\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [Mahasiswa\BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [Mahasiswa\BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{borrowing}', [Mahasiswa\BorrowingController::class, 'show'])->name('borrowings.show');

    // Profile
    Route::get('/profile', [Mahasiswa\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Mahasiswa\ProfileController::class, 'update'])->name('profile.update');
});

// Fallback for Windows php artisan serve bug with symlinks
if (app()->environment('local')) {
    Route::get('/storage/{path}', function ($path) {
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            return response()->file($fullPath);
        }
        abort(404);
    })->where('path', '.*');
}
