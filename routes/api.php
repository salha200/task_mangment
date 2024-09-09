<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route::resources([
//     'roles' => RoleController::class,
//     'users' => UserController::class,
//     'products' => ProductController::class,
// ]);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});


Route::middleware('auth:api')->group(function () {
    // Route لعرض جميع المهام
    Route::get('/tasks', [TaskController::class, 'index']);

    // Route لإضافة مهمة جديدة
    Route::post('/tasks', [TaskController::class, 'store']);

    // Route لعرض مهمة معينة
    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    // Route لتحديث مهمة معينة
    Route::put('/tasks/{task}', [TaskController::class, 'update']);

    // Route لحذف مهمة معينة
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Route لتعيين مهمة إلى مستخدم
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assignTask']);
});
