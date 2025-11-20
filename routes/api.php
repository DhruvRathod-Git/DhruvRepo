<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\PurchaseController;
use App\Models\Orders;
use App\Models\Customer;
use App\models\User;
use App\Models\Product;


Route::post('/register', [AuthController::class, 'register']); // Register
Route::post('/login', [AuthController::class, 'login']);  //  Login

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']); // Logout

    Route::get('/product', [ProductController::class, 'index']); // List all products
    Route::get('/product/{id}', [ProductController::class, 'show']); // Show single product 
    Route::post('/products', [ProductController::class, 'store']); // Create product

    Route::get('/purchase', [PurchaseController::class, 'index']); // Purchase products lists
    Route::post('/purchase', [PurchaseController::class, 'store']); // Purchase products
    Route::put('purchase/{id}/cancel', [PurchaseController::class, 'cancel']); // Cancel order
    Route::get('purchase/{id}/invoice', [PurchaseController::class, 'invoice']); // Generate invoice
});