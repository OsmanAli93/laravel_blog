<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerificationController;


// Public Routes
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
  ->middleware(['signed'])
  ->name('verification.verify');

Route::get('/test', function (Request $request) {
    return response()->json([
        'message' => 'Hello World'
    ], 200);
});



Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
    Route::post('/forgot-password', 'forgotPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});


// Private Routes
Route::group(['middleware' => ['auth:sanctum']], function() {
  Route::post('/logout',   [AuthController::class, 'logout']);
});

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

