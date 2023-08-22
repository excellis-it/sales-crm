<?php

use App\Http\Controllers\Admin\AccountManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ForgetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\SalesManager\ProfileController as SalesManagerProfileController;
use App\Http\Controllers\SalesManager\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Clear cache
Route::get('clear', function () {
    Artisan::call('optimize:clear');
    return "Optimize clear has been successfully";
});

Route::get('/', [AuthController::class, 'login'])->name('admin.login');
Route::post('/login-check', [AuthController::class, 'loginCheck'])->name('admin.login.check');  //login check
Route::post('forget-password', [ForgetPasswordController::class, 'forgetPassword'])->name('admin.forget.password');
Route::post('change-password', [ForgetPasswordController::class, 'changePassword'])->name('admin.change.password');
Route::get('forget-password/show', [ForgetPasswordController::class, 'forgetPasswordShow'])->name('admin.forget.password.show');
Route::get('reset-password/{id}/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('admin.reset.password');
Route::post('change-password', [ForgetPasswordController::class, 'changePassword'])->name('admin.change.password');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('admin.profile.update');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [ProfileController::class, 'password'])->name('admin.password'); // password change
        Route::post('/update', [ProfileController::class, 'passwordUpdate'])->name('admin.password.update'); // password update
    });

    Route::resources([
        'sales_managers' => CustomerController::class,
        'account_managers' => AccountManagerController::class,
    ]);
    //  Sales manager Routes
    Route::prefix('sales_managers')->group(function () {
        Route::get('/sales_manager-delete/{id}', [CustomerController::class, 'delete'])->name('sales_managers.delete');
    });
    Route::get('/changeCustomerStatus', [CustomerController::class, 'changeCustomersStatus'])->name('sales_managers.change-status');
    
    //  Sales manager Routes
     Route::prefix('account_managers')->group(function () {
        Route::get('/account_manager-delete/{id}', [AccountManagerController::class, 'delete'])->name('account_managers.delete');
    });
    Route::get('/changeAccountManagerStatus', [AccountManagerController::class, 'changeAccountManagerStatus'])->name('account_managers.change-status');
});

/**---------------------------------------------------------------Sales Manager ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['SalesManager'], 'prefix' => 'sales-manager'], function () {
    Route::get('profile', [SalesManagerProfileController::class, 'index'])->name('sales-manager.profile');
    Route::post('profile/update', [SalesManagerProfileController::class, 'profileUpdate'])->name('sales-manager.profile.update');
    Route::get('logout', [AuthController::class, 'logout'])->name('sales-manager.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [SalesManagerProfileController::class, 'password'])->name('sales-manager.password'); // password change
        Route::post('/update', [SalesManagerProfileController::class, 'passwordUpdate'])->name('sales-manager.password.update'); // password update
    });

    Route::resources([
        'projects' => ProjectController::class,
    ]);
});