<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// مسارات تحتاج تسجيل دخول
Route::middleware('auth:sanctum')->group(function () {

    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);

    // بيانات المستخدم الحالي
    Route::get('/user', [UserController::class, 'show']);

    // تعديل بيانات المستخدم الحالي
    Route::put('/user', [UserController::class, 'update']);


    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);



    // الحجوزات الخاصة بالمستخدم الحالي
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);

    // إنشاء حجز جديد (المستخدم فقط)
    Route::post('/bookings', [BookingController::class, 'store']);

    // تحديث وحذف الحجز: مسموح للمستخدم صاحب الحجز فقط (تحقق داخل الكنترولر)
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);

    // عرض حجز معين - المستخدم أو الأدمن فقط
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);

    // صفحات ومهام الأدمن (محمية بميدل وير admin)
    Route::middleware('admin')->group(function () {

        // كل الحجوزات (للأدمن)
        Route::get('/bookings', [BookingController::class, 'allBookings']);

        // قبول / رفض الحجز
        Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm']);
        Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject']);

        // إدارة الأقسام (Categories)
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

        // إدارة عناصر المنيو (Menu Items)
        Route::apiResource('menu-items', MenuItemController::class)->except(['index', 'show', 'getByCategory']);
        // عرض كل المستخدمين (مثلاً للأدمن فقط)
        Route::get('/users', [UserController::class, 'index']);
    });
});

// مسارات مفتوحة للجميع بدون تسجيل دخول
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/menu-items', [MenuItemController::class, 'index']);
Route::get('/menu-items/{id}', [MenuItemController::class, 'show']);
Route::get('/categories/{id}/menu-items', [MenuItemController::class, 'getByCategory']);
