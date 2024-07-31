<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PostLikeController;
use App\Http\Controllers\Api\PostCommentController;
use App\Http\Controllers\Api\EmailVerificationController;


// Public Routes

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

Route::get('/posts/{slug}/comments', [PostCommentController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.reset');
Route::patch('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


// Private Routes
Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware(['throttle:6,1']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::post('/profile/{id}', [ProfileController::class, 'update']);

    Route::post('/posts', [PostController::class, 'store']);

    Route::post('/posts/{slug}/likes', [PostLikeController::class, 'store']);
    Route::delete('/posts/{slug}/likes', [PostLikeController::class, 'destroy']);

    Route::post('/posts/{slug}/comments', [PostCommentController::class, 'store']);

});

