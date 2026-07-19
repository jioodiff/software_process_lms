<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BorrowingController;

Route::prefix('v1')->group(function () {
    Route::get('/borrowings/overdue', [BorrowingController::class, 'getOverdue'])
        ->middleware('n8n.auth')
        ->name('api.borrowings.overdue');
});
