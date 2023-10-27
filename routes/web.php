<?php

use App\Http\Controllers\AccountManager\DashboardController as AccountManagerDashboardController;
use App\Http\Controllers\AccountManager\ProfileController as AccountManagerProfileController;
use App\Http\Controllers\AccountManager\ProjectController as AccountManagerProjectController;
use App\Http\Controllers\Admin\AccountManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ForgetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BusinessDevelopmentExcecutiveController;
use App\Http\Controllers\Admin\BusinessDevelopmentManagerController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GoalsController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProspectController as AdminProspectController;
use App\Http\Controllers\Admin\SalesExcecutiveController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\BDM\BusinessDevelopmentExcecutiveController as BDMBusinessDevelopmentExcecutiveController;
use App\Http\Controllers\BDM\DashboardController as BDMDashboardController;
use App\Http\Controllers\BDM\ProfileController as BDMProfileController;
use App\Http\Controllers\BDM\ProjectController as BDMProjectController;
use App\Http\Controllers\BDM\ProspectController as BDMProspectController;
use App\Http\Controllers\SalesExcecutive\DashboardController as SalesExcecutiveDashboardController;
use App\Http\Controllers\SalesExcecutive\ProfileController as SalesExcecutiveProfileController;
use App\Http\Controllers\SalesExcecutive\ProjectController as SalesExcecutiveProjectController;
use App\Http\Controllers\SalesExcecutive\ProspectController;
use App\Http\Controllers\SalesManager\DashboardController as SalesManagerDashboardController;
use App\Http\Controllers\SalesManager\ProfileController as SalesManagerProfileController;
use App\Http\Controllers\SalesManager\ProjectController;
use App\Http\Controllers\SalesManager\ProspectController as SalesManagerProspectController;
use App\Http\Controllers\SalesManager\SalesExcecutiveController as SalesManagerSalesExcecutiveController;
use Illuminate\Support\Facades\Artisan;

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
        'goals' => GoalsController::class,
        'business-development-managers' => BusinessDevelopmentManagerController::class,
        'business-development-excecutive' => BusinessDevelopmentExcecutiveController::class,
    ]);

    Route::name('admin.')->group(function () {
        Route::resources([
            'prospects' => AdminProspectController::class,
        ]);
        Route::get('/prospects-delete/{id}', [AdminProspectController::class, 'delete'])->name('prospects.delete');
        Route::get('/filter', [AdminProspectController::class, 'filter'])->name('prospects.filter'); // filter
        Route::get('/assign-to-project/{id}', [AdminProspectController::class, 'assignToProject'])->name('prospects.assign-project'); // assign project
    });
    //list goals
    Route::get('/goals-list', [GoalsController::class, 'goalsList'])->name('goals.ajax-list');
    //prospect list
    Route::get('/prospect-ajax-list', [AdminProspectController::class, 'prospectAjaxList'])->name('prospect.ajax-list');

    // delete project
    Route::get('/project-ajax-list', [AdminProjectController::class, 'ajaxList'])->name('sales-projects.ajax-list');
    Route::get('/project-delete/{id}', [AdminProjectController::class, 'delete'])->name('sales-projects.delete');
    Route::get('/projectAssignTo', [AdminProjectController::class, 'projectAssignTo'])->name('sales-projects.updateAssignedTo');
    Route::get('/projectDocumentDownload/{id}', [AdminProjectController::class, 'DocumentDownload'])->name('sales-projects.document.download');


    //  Sales manager Routes
    Route::prefix('sales_managers')->group(function () {
        Route::get('/sales_manager-delete/{id}', [CustomerController::class, 'delete'])->name('sales_managers.delete');
    });
    Route::get('/changeCustomerStatus', [CustomerController::class, 'changeCustomersStatus'])->name('sales_managers.change-status');

    //  Business Development manager Routes
    Route::prefix('business-development-managers')->group(function () {
        Route::get('/business-development-manager-delete/{id}', [BusinessDevelopmentManagerController::class, 'delete'])->name('business-development-managers.delete');
    });

    Route::get('/changeBusinessDevelopmentManagerStatus', [BusinessDevelopmentManagerController::class, 'changeBusinessDevelopmentManagerStatus'])->name('business-development-managers.change-status');
    // Business Development Excecutive Routes
    Route::prefix('business-development-excecutive')->group(function () {
        Route::get('/business-development-excecutive-delete/{id}', [BusinessDevelopmentExcecutiveController::class, 'delete'])->name('business-development-excecutive.delete');
    });
    Route::get('/changeBusinessDevelopmentExcecutiveStatus', [BusinessDevelopmentExcecutiveController::class, 'changeBusinessDevelopmentExcecutiveStatus'])->name('business-development-excecutive.change-status');
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
    // Goals Routes
    Route::prefix('goals')->group(function () {
        Route::get('/goals-delete/{id}', [GoalsController::class, 'delete'])->name('goals.delete');
    });
    Route::post('/goals-get-user', [GoalsController::class, 'getUser'])->name('goals.get.user');
    Route::post('/goals-get-user-by-type', [GoalsController::class, 'getUserByType'])->name('goals.get.user-by-type');
});

/**---------------------------------------------------------------Sales Manager ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['SalesManager'], 'prefix' => 'sales-manager'], function () {

    Route::get('dashboard', [SalesManagerDashboardController::class, 'index'])->name('sales-manager.dashboard');
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
            'sales-excecutive' => SalesManagerSalesExcecutiveController::class,

        ]);
    });

    Route::get('/filter', [SalesManagerProspectController::class, 'filter'])->name('sales-manager.prospects.filter'); // filter
    Route::get('/assign-to-project/{id}', [SalesManagerProspectController::class, 'assignToProject'])->name('sales-manager.prospects.assign-project'); // assign project
    // change status sales excecutive
    Route::get('/changeSalesExcecutiveStatus', [SalesManagerSalesExcecutiveController::class, 'changeSalesExcecutiveStatus'])->name('sales-manager.sales-excecutive.change-status');
    // Sales Excecutive Routes
    Route::prefix('sales-excecutive')->group(function () {
        Route::get('/sales-excecutive-delete/{id}', [SalesManagerSalesExcecutiveController::class, 'delete'])->name('sales-manager.sales-excecutive.delete');
    });
    // delete project
    Route::get('/project-delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
    // Route::get('/project-document/{id}', [ProjectController::class, 'projectDocument'])->name('projects.document');
    Route::get('/project-document_download/{id}', [ProjectController::class, 'projectDocumentDownload'])->name('projects.document.download');
});

/**---------------------------------------------------------------Account Manager ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['AccountManager'], 'prefix' => 'account-manager'], function () {
    Route::get('dashboard', [AccountManagerDashboardController::class, 'index'])->name('account-manager.dashboard');
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

    Route::get('/projects-document_download/{id}', [AccountManagerProjectController::class, 'accountManagerdocumentDownload'])->name('account-manager.projects.document.download');
});

/**---------------------------------------------------------------Sales Excecutive ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['SalesExcecutive'], 'prefix' => 'sales-excecutive'], function () {
    Route::get('dashboard', [SalesExcecutiveDashboardController::class, 'index'])->name('sales-excecutive.dashboard');
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

    Route::name('sales-excecutive.')->group(function () {
        Route::resources([
            'projects' => SalesExcecutiveProjectController::class,
        ]);
    });

    //project list
    Route::get('/project-ajax-list', [SalesExcecutiveProjectController::class, 'projectAjaxList'])->name('sales-excecutive.projects.ajax-list');

    Route::get('/filter', [ProspectController::class, 'filter'])->name('prospects.filter'); // filter
    Route::get('/assign-to-project/{id}', [ProspectController::class, 'assignToProject'])->name('prospects.assign-project'); // assign project

});

/**---------------------------------------------------------------Business Development Manager ---------------------------------------------------------------------------------- */

Route::group(['middleware' => ['BDM'], 'prefix' => 'bdm'], function () {
    Route::get('dashboard', [BDMDashboardController::class, 'index'])->name('bdm.dashboard');
    Route::get('profile', [BDMProfileController::class, 'index'])->name('bdm.profile');
    Route::post('profile/update', [BDMProfileController::class, 'profileUpdate'])->name('bdm.profile.update');
    Route::get('logout', [AuthController::class, 'BusinessDevelopmentManagerlogout'])->name('bdm.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [BDMProfileController::class, 'password'])->name('bdm.password'); // password change
        Route::post('/update', [BDMProfileController::class, 'passwordUpdate'])->name('bdm.password.update'); // password update
    });
    Route::name('bdm.')->group(function () {
        Route::resources([
            'projects' => BDMProjectController::class,
            'prospects' => BDMProspectController::class,
        ]);
        Route::get('/bdm-prospect-ajax-list', [BDMProspectController::class, 'prospectAjaxList'])->name('prospect.ajax-list');
        // delete prospect
        Route::get('/prospect-delete/{id}', [BDMProspectController::class, 'delete'])->name('prospects.delete');
    });

    Route::resources([
        'bde' => BDMBusinessDevelopmentExcecutiveController::class,
    ]);
    Route::get('/project-document_download/{id}', [BDMProjectController::class, 'projectDocumentDownload'])->name('bdm.projects.document.download');
    // delete project
    Route::get('/project-delete/{id}', [BDMProjectController::class, 'delete'])->name('bdm.projects.delete');
    //project list
    Route::get('/bdm-project-ajax-list', [BDMProjectController::class, 'ajaxList'])->name('bdm.projects.ajax-list');

    Route::prefix('bde')->group(function () {
        Route::get('/bde-delete/{id}', [BDMBusinessDevelopmentExcecutiveController::class, 'delete'])->name('bde.delete');
    });
    Route::get('/changeBDEStatus', [BDMBusinessDevelopmentExcecutiveController::class, 'changeBDEStatus'])->name('bde.change-status');
});
