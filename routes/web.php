<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PenaltyController;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');

Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ItemController::class, 'index'])->name('dashboard');
    
    // Items
    Route::resource('items', ItemController::class);
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my-items');
    Route::patch('/items/{item}/toggle-status', [ItemController::class, 'toggleStatus'])->name('items.toggle-status');
    Route::delete('/items/{item}/photos/{photo}', [ItemController::class, 'deletePhoto'])->name('items.delete-photo');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Item Likes
    Route::post('/items/{item}/like', [ProfileController::class, 'toggleLike'])->name('items.like');
    Route::delete('/items/{item}/unlike', [ProfileController::class, 'toggleLike'])->name('items.unlike');

    // Ratings & Reviews
    Route::get('/borrow-requests/{borrowRequest}/rate', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/borrow-requests/{borrowRequest}/rate', [RatingController::class, 'store'])->name('ratings.store');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/start', [MessageController::class, 'startConversation'])->name('messages.start');
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');

    // Borrow Requests
    Route::get('/borrow-requests', [BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::post('/borrow-requests', [BorrowRequestController::class, 'store'])->name('borrow-requests.store');
    Route::patch('/borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::patch('/borrow-requests/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::patch('/borrow-requests/{borrowRequest}/cancel', [BorrowRequestController::class, 'cancel'])->name('borrow-requests.cancel');
    Route::patch('/borrow-requests/{borrowRequest}/borrowed', [BorrowRequestController::class, 'markBorrowed'])->name('borrow-requests.borrowed');
    Route::patch('/borrow-requests/{borrowRequest}/returned', [BorrowRequestController::class, 'markReturned'])->name('borrow-requests.returned');
    Route::post('/borrow-requests/{borrowRequest}/report-damage', [BorrowRequestController::class, 'reportDamage'])->name('borrow-requests.report-damage');
    Route::post('/borrow-requests/{borrowRequest}/missing', [BorrowRequestController::class, 'markMissing'])->name('borrow-requests.missing');

    // Penalties
    Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('about');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
    Route::patch('/users/{user}/unsuspend', [AdminController::class, 'unsuspendUser'])->name('admin.users.unsuspend');
    Route::post('/users/{user}/adjust-points', [AdminController::class, 'adjustPoints'])->name('admin.users.adjust-points');
    
    // Item Management
    Route::get('/items', [AdminController::class, 'items'])->name('admin.items');
    Route::patch('/items/{item}/toggle-status', [AdminController::class, 'toggleItemStatus'])->name('admin.items.toggle-status');
    Route::delete('/items/{item}', [AdminController::class, 'deleteItem'])->name('admin.items.delete');
    
    // Borrow Request Management
    Route::get('/borrow-requests', [AdminController::class, 'borrowRequests'])->name('admin.borrow-requests');
    
    // Penalties Management
    Route::get('/penalties', [AdminController::class, 'penalties'])->name('admin.penalties');
    Route::patch('/penalties/{penalty}/approve', [AdminController::class, 'approvePenalty'])->name('admin.penalties.approve');
    Route::patch('/penalties/{penalty}/reject', [AdminController::class, 'rejectPenalty'])->name('admin.penalties.reject');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});
