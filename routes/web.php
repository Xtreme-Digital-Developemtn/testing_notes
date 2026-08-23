<?php

use App\Http\Controllers\Admin\AdminClientController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientMessageController;
use App\Http\Controllers\Client\ClientNotificationController;
use App\Http\Controllers\Client\ClientRequestController;
use App\Http\Controllers\Client\ClientStorageController;
use App\Models\MediaFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\AuthController;

Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');

    Route::get('/requests', [ClientRequestController::class, 'index'])->name('client.requests.index');
    Route::get('/requests/create', [ClientRequestController::class, 'create'])->name('client.requests.create');
    Route::post('/requests', [ClientRequestController::class, 'store'])->name('client.requests.store');
    Route::get('/requests/{id}', [ClientRequestController::class, 'show'])->name('client.requests.show');
    Route::post('/requests/{id}/reply', [ClientRequestController::class, 'reply'])->name('client.requests.reply');

    Route::get('/notifications', [ClientNotificationController::class, 'index'])->name('client.notifications.index');

    Route::get('/messages', [ClientMessageController::class, 'index'])->name('client.messages.index');
    Route::post('/messages/{id}/read', [ClientMessageController::class, 'markAsRead'])->name('client.messages.read');

    Route::get('/storage-files', [ClientStorageController::class, 'index'])->name('client.storage.index');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    Route::get('/requests/{id}', [AdminRequestController::class, 'show'])->name('admin.requests.show');
    Route::patch('/requests/{id}/status', [AdminRequestController::class, 'updateStatus'])->name('admin.requests.updateStatus');
    Route::post('/requests/{id}/reply', [AdminRequestController::class, 'reply'])->name('admin.requests.reply');

    Route::get('/messages/create', [AdminMessageController::class, 'create'])->name('admin.messages.create');
    Route::post('/messages', [AdminMessageController::class, 'store'])->name('admin.messages.store');

    Route::get('/clients', [AdminClientController::class, 'index'])->name('admin.clients.index');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
});

/*
|--------------------------------------------------------------------------
| Storage (serve files)
|--------------------------------------------------------------------------
*/
Route::get('/storage/serve/{id}', function ($id) {
    $file = MediaFile::findOrFail($id);

    if (Auth::guard('admin')->check() || $file->user_id === Auth::id()) {
        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    abort(403);
})->name('storage.serve');

Route::get('/storage/inline/{id}', function ($id) {
    $file = MediaFile::findOrFail($id);

    if (Auth::guard('admin')->check() || $file->user_id === Auth::id()) {
        $mime = Storage::disk('local')->mimeType($file->path);
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ];
        return Storage::disk('local')->response($file->path, $file->original_name, $headers);
    }

    abort(403);
})->name('storage.inline');
