<?php

use App\Http\Controllers\AccountManager\ProfileController as AccountManagerProfileController;
use App\Http\Controllers\AccountManager\ProjectController as AccountManagerProjectController;
use App\Http\Controllers\Admin\AccountManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ForgetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProspectController as AdminProspectController;
use App\Http\Controllers\Admin\SalesExcecutiveController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\SalesExcecutive\ProfileController as SalesExcecutiveProfileController;
use App\Http\Controllers\SalesExcecutive\ProspectController;
use App\Http\Controllers\SalesManager\ProfileController as SalesManagerProfileController;
use App\Http\Controllers\SalesManager\ProjectController;
use App\Http\Controllers\SalesManager\ProspectController as SalesManagerProspectController;

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
        'sales-projects' => AdminProjectController::class,
        'sales-excecutive' => SalesExcecutiveController::class,
    ]);

    Route::name('admin.')->group(function () {
        Route::resources([
            'prospects' => AdminProspectController::class,
        ]);
        Route::get('/prospects-delete/{id}', [AdminProspectController::class, 'delete'])->name('prospects.delete');
    });
    // delete project
    Route::get('/project-delete/{id}', [AdminProjectController::class, 'delete'])->name('sales-projects.delete');
    Route::get('/projectAssignTo', [AdminProjectController::class, 'projectAssignTo'])->name('sales-projects.updateAssignedTo');

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

    // Sales Excecutive Routes
    Route::prefix('sales-excecutive')->group(function () {
        Route::get('/sales-excecutive-delete/{id}', [SalesExcecutiveController::class, 'delete'])->name('sales-excecutive.delete');
    });
    Route::get('/changeSalesExcecutiveStatus', [SalesExcecutiveController::class, 'changeSalesExcecutiveStatus'])->name('sales-excecutive.change-status');
});

/**---------------------------------------------------------------Sales Manager ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['SalesManager'], 'prefix' => 'sales-manager'], function () {
    Route::get('profile', [SalesManagerProfileController::class, 'index'])->name('sales-manager.profile');
    Route::post('profile/update', [SalesManagerProfileController::class, 'profileUpdate'])->name('sales-manager.profile.update');
    Route::get('logout', [AuthController::class, 'SalesManagerlogout'])->name('sales-manager.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [SalesManagerProfileController::class, 'password'])->name('sales-manager.password'); // password change
        Route::post('/update', [SalesManagerProfileController::class, 'passwordUpdate'])->name('sales-manager.password.update'); // password update
    });

    Route::resources([
        'projects' => ProjectController::class,
    ]);

    Route::name('sales-manager.')->group(function () {
        Route::resources([
            'prospects' => SalesManagerProspectController::class,
        ]);
    });
    // delete project
    Route::get('/project-delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
});

/**---------------------------------------------------------------Account Manager ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['AccountManager'], 'prefix' => 'account-manager'], function () {
    Route::get('profile', [AccountManagerProfileController::class, 'index'])->name('account-manager.profile');
    Route::post('profile/update', [AccountManagerProfileController::class, 'profileUpdate'])->name('account-manager.profile.update');
    Route::get('logout', [AuthController::class, 'AccountManagerlogout'])->name('account-manager.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [AccountManagerProfileController::class, 'password'])->name('account-manager.password'); // password change
        Route::post('/update', [AccountManagerProfileController::class, 'passwordUpdate'])->name('account-manager.password.update'); // password update
    });
    Route::name('account-manager.')->group(function () {
        Route::resources([
            'projects' => AccountManagerProjectController::class,
        ]);
    });
});

/**---------------------------------------------------------------Sales Excecutive ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['SalesExcecutive'], 'prefix' => 'sales-excecutive'], function () {
    Route::get('profile', [SalesExcecutiveProfileController::class, 'index'])->name('sales-excecutive.profile');
    Route::post('profile/update', [SalesExcecutiveProfileController::class, 'profileUpdate'])->name('sales-excecutive.profile.update');
    Route::get('logout', [AuthController::class, 'SalesExcecutivelogout'])->name('sales-excecutive.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [SalesExcecutiveProfileController::class, 'password'])->name('sales-excecutive.password'); // password change
        Route::post('/update', [SalesExcecutiveProfileController::class, 'passwordUpdate'])->name('sales-excecutive.password.update'); // password update
    });
    Route::resources([
        'prospects' => ProspectController::class,
    ]);
});
