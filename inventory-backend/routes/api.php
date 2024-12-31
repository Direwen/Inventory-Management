<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\InventoryCollaboratorController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes (No authentication required)
Route::prefix('auth')->group(function () {
    // Sign up a new user
    Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');
    // Log in an existing user
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// Email verification routes
Route::prefix('email')->group(function () {
    // Verify email using a signed URL
    Route::get('/verify/{id}', [AuthController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    // Resend email verification link
    Route::post('/resend', [AuthController::class, 'resend'])->name('verification.send');
});

// To Send Restoration Token
Route::post('user/request-reactivation-token', [AuthController::class, 'sendReactivationToken']);
// Restore user account
Route::post('user/restore', [UserController::class, 'restore']);
// To send password reset link to user
Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])
->name('password.email');
// To redirect user to password reset page
Route::get('/reset-password/{token}', [PasswordController::class, 'redirectToResetPage'])
->name('password.reset');
// To reset and change the password 
Route::post('/reset-password', [PasswordController::class, 'reset'])
->name('password.update');

// Routes that require authentication
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Log out the authenticated user
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // User-related routes
    Route::prefix('user')->group(function () {
        // View the current user's profile
        Route::get('/', [UserController::class, 'show'])->name('user.me');
        // Get User Invitations
        Route::get("/invitations", [InvitationController::class, 'indexForMe'])->name('user.invitations');
        // Get User Notifications
        Route::get("/notifications", [NotificationController::class, 'index'])->name('notifications.index');
        // Mark User Notifications As Read
        Route::put("/notifications", [NotificationController::class, 'update'])->name('notifications.update');
        // Update the user's profile
        Route::put('/update', [UserController::class, 'update'])->name('user.update');
        // Delete the user's account
        Route::delete('/delete', [UserController::class, 'destroy'])->name('user.destroy');
        // Update the user's password
        Route::put('/update-password', [PasswordController::class, 'update'])->name('password.update');
    });

    // Inventory-related routes
    Route::prefix('inventories')->group(function () {
        // Create a new inventory
        Route::post('/', [InventoryController::class, 'store'])->name('inventories.store');
        // List all inventories
        Route::get('/', [InventoryController::class, 'index'])->name('inventories.index');
        // View a specific inventory (requires "can:view" policy)
        Route::get('/{inventory}', [InventoryController::class, 'show'])->middleware('can:view,inventory')->name('inventories.show');
        // Update a specific inventory (requires "can:update" policy)
        Route::put('/{inventory}', [InventoryController::class, 'update'])->middleware('can:update,inventory')->name('inventories.update');
        // Delete a specific inventory (requires "can:delete" policy)
        Route::delete('/{inventory}', [InventoryController::class, 'destroy'])->middleware('can:delete,inventory')->name('inventories.destroy');

        // Collaborator management routes
        Route::prefix('/{inventory}/collabs')->group(function () {
            // Route::get('/', [InventoryCollaboratorController::class, 'index'])->name('inventory_collaborator.index');
            Route::get('/', [InventoryCollaboratorController::class, 'show'])->name('inventory_collaborator.show');
            Route::post('/', [InventoryCollaboratorController::class, 'store'])->middleware('can:manageRoles,inventory')->name('inventory_collaborator.store');
            Route::put('/', [InventoryCollaboratorController::class, 'update'])->middleware('can:manageRoles,inventory')->name('inventory_collaborator.update');
            Route::delete('/{collab}', [InventoryCollaboratorController::class, 'destroy'])->middleware('can:manageRoles,inventory')->name('inventory_collaborator.destroy');
        });
            
        // Product-related routes within an inventory
        Route::prefix('/{inventory}/products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->middleware('can:viewProduct,inventory')->name('products.index');
            Route::get('/{product}', [ProductController::class, 'show'])->middleware('can:viewProduct,inventory')->name('products.show');
            Route::post('/', [ProductController::class, 'store'])->middleware('can:storeProduct,inventory')->name('products.store');
            Route::put('/{product}', [ProductController::class, 'update'])->middleware('can:updateProduct,inventory')->name('products.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('can:destroyProduct,inventory')->name('products.destroy');
            Route::post('/{product}/inbound', [InboundController::class, 'store'])->middleware('can:adjustStock,inventory')->name("inbounds.store");
            Route::post('/{product}/outbound', [OutboundController::class, 'store'])->middleware('can:adjustStock,inventory')->name("outbounds.store");
        });

        Route::prefix("/{inventory}/invitations")->group(function () {
            Route::get("/", [InvitationController::class, "index"])->name("inventories.index");
            Route::get("/{invitation}", [InvitationController::class, "show"])->name("inventories.show");
            Route::post("/", [InvitationController::class, "store"])->middleware('can:storeInvitation,inventory')->name("inventories.store");
            Route::put("/{invitation}", [InvitationController::class, "update"])->middleware('can:updateInvitation,inventory,invitation')->name("inventories.update");
        });

        // Log related routes
        Route::prefix("/{inventory}")->group(function () {
            Route::get("/logs", [LogController::class, "index"])->name("logs.index");
        });
    });
});
