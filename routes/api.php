<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AvatarImgController;

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



Route::post("register",[RegisterController::class,"register"] );
Route::post("login",[LoginController::class,"login"] );

//con autentication, solo admin
Route::middleware(['auth:sanctum'])->group(function () { //, 'role:admin'

    Route::post('/user', [UserController::class, 'createUser']);
    Route::get('/user/{id}', [UserController::class, 'getUserById']);
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::put('/user/{id}', [UserController::class, 'updateUserById']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUserById']);

});

//con autentication, todos (user, premium and admin)
Route::middleware(['auth:sanctum'])->group(function () { //, 'role:admin,user'
    //gestion usuario
    Route::post('/logout', [LoginController::class, 'logout']);    
    Route::get('/user', [UserController::class, 'getUser']); 
    Route::put('/user', [UserController::class, 'updateUser']);
    Route::delete('/user', [UserController::class, 'deleteUser']);
    //avatar
    Route::post('/users/{id}/update-avatar', [AvatarImgController::class, 'updateAvatar']);
    Route::get('user/{id}/avatar', [AvatarImgController::class, 'getAvatar']);
    //planos de gastos
    Route::get('/data/names', [DataController::class, 'getDataNames']);
    Route::get('/data', [DataController::class, 'index']);
    Route::get('/data/{id}', [DataController::class, 'getPlano']);
    Route::post('/data', [DataController::class, 'store']);
    Route::delete('/data/{id}', [DataController::class, 'deletePlano']);
});
