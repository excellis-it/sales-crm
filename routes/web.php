<?php

use App\Http\Controllers\AccountManager\DashboardController as AccountManagerDashboardController;
use App\Http\Controllers\AccountManager\FollowupController;
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
use App\Http\Controllers\Admin\FollowupController as AdminFollowupController;
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
use App\Http\Controllers\BDE\DashboardController as BDEDashboardController;
use App\Http\Controllers\BDE\ProspectController as BDEProspectController;
use App\Http\Controllers\BDE\ProjectController as BDEProjectController;
use App\Http\Controllers\BDE\ProfileController as BDEProfileController;
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
            'followups' => AdminFollowupController::class,
        ]);
    });
    Route::get('/admin-followups-filter', [AdminFollowupController::class, 'adminFollowupProject'])->name('admin.followups.filter');
    // search
    Route::get('/earning-statistics', [DashboardController::class,'getEarningStatistics'])->name('admin.dashboard.earning-statistics');

    Route::get('/prospect-search', [DashboardController::class, 'dashboardProspectFetch'])->name('admin.dashboard.prospect-fetch-data');
    Route::get('/goals-search', [GoalsController::class, 'search'])->name('project-goals.search');
    Route::get('/sales-managers-search', [CustomerController::class, 'search'])->name('sales_managers.search');
    Route::get('/account-managers-search', [AccountManagerController::class, 'search'])->name('account_managers.search');
    Route::get('/sales-excecutive-search', [SalesExcecutiveController::class, 'search'])->name('sales-excecutive.search');
    Route::get('/business-development-managers-search', [BusinessDevelopmentManagerController::class, 'search'])->name('business-development-managers.search');
    Route::get('/business-development-excecutive-search', [BusinessDevelopmentExcecutiveController::class, 'search'])->name('business-development-excecutive.search');

    // fetch data
    Route::get('/fetch-data', [AdminProjectController::class, 'fetchData'])->name('sales-projects.fetch-data');
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
    Route::get('/new-customer', [AdminProjectController::class, 'newCustomer'])->name('sales-projects.new-customer');
    Route::get('/customer-details', [AdminProjectController::class, 'customerDetails'])->name('sales-projects.customer-details');


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

    Route::get('/prospect-search', [SalesManagerDashboardController::class, 'dashboardProspectSearch'])->name('sales-manger.dashboard.prospect-search-data');
    Route::get('/sales-executive-search', [SalesManagerSalesExcecutiveController::class, 'salesExecutiveSerach'])->name('sales-manager.sales-excecutive.search');
    Route::get('/projects-filter', [ProjectController::class, 'filterProject'])->name('sales-manager.project.filter');
    Route::get('/prospect-filter', [SalesManagerProspectController::class, 'prospectFilter'])->name('sales-manager.prospects.filter');
    Route::get('/prospect-status-filter',[SalesManagerProspectController::class, 'prospectStatusFilter'])->name('sales-manager.prospects.status-filter'); // filter
    Route::get('/assign-to-project/{id}', [SalesManagerProspectController::class, 'assignToProject'])->name('sales-manager.prospects.assign-project'); // assign project
    // change status sales excecutive
    Route::get('/changeSalesExcecutiveStatus', [SalesManagerSalesExcecutiveController::class, 'changeSalesExcecutiveStatus'])->name('sales-manager.sales-excecutive.change-status');
    // Sales Excecutive Routes
    Route::prefix('sales-excecutive')->group(function () {
        Route::get('/sales-excecutive-delete/{id}', [SalesManagerSalesExcecutiveController::class, 'delete'])->name('sales-manager.sales-excecutive.delete');
    });
    // delete project
    // Route::get('/project-delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
    // Route::get('/project-document/{id}', [ProjectController::class, 'projectDocument'])->name('projects.document');
    Route::get('/project-document_download/{id}', [ProjectController::class, 'projectDocumentDownload'])->name('projects.document.download');
    Route::get('/project-delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
    Route::get('/new-customer', [ProjectController::class, 'newCustomer'])->name('projects.new-customer');
    Route::get('/customer-details', [ProjectController::class, 'customerDetails'])->name('projects.customer-details');
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
            'followups' => FollowupController::class,
        ]);
        Route::get('/new-customer', [AccountManagerProjectController::class, 'newCustomer'])->name('projects.new-customer');
        Route::get('/customer-details', [AccountManagerProjectController::class, 'customerDetails'])->name('projects.customer-details');
    });

    Route::get('/account-manager-projects-filter', [AccountManagerProjectController::class, 'accountManagerFilterProject'])->name('account-manager.project.filter');
    Route::get('/account-manager-followups-filter', [FollowupController::class, 'accountManagerFollowupProject'])->name('account-manager.followups.filter');
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
    Route::get('/sales-executive-prospects-search', [SalesExcecutiveDashboardController::class, 'salesExecutiveDashboardProspectSearch'])->name('sales-executive.dashboard.prospect-search-data');
    Route::get('/sales-executive-projects-filter', [SalesExcecutiveProjectController::class, 'salesExecutiveProjectFilter'])->name('sales-excecutive.project.filter');
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
        // filter prospects
        Route::get('/prospect-delete/{id}', [BDMProspectController::class, 'delete'])->name('prospects.delete');

    });

    Route::resources([
        'bde' => BDMBusinessDevelopmentExcecutiveController::class,
    ]);

    Route::get('/bdm-business-development-executive-search', [BDMBusinessDevelopmentExcecutiveController::class, 'bdmBusinessDevelopmentExecutiveSearch'])->name('bdm.business-development-executive.search');
    Route::get('/bdm-prospects-search', [BDMDashboardController::class, 'bdmDashboardProspectSearch'])->name('bdm.dashboard.prospect-search-data');
    Route::get('/bdm-projects-filter', [BDMProjectController::class, 'bdmProjectFilter'])->name('bdm.project.filter');
    Route::get('/bdm-prospect-filter', [BDMProspectController::class, 'bdmProspectFilter'])->name('bdm.prospects.filter');
    Route::get('/project-document_download/{id}', [BDMProjectController::class, 'projectDocumentDownload'])->name('bdm.projects.document.download');
    // delete project
    // Route::get('/project-delete/{id}', [BDMProjectController::class, 'delete'])->name('bdm.projects.delete');
    Route::get('/new-customer', [BDMProjectController::class, 'newCustomer'])->name('bdm.projects.new-customer');
    Route::get('/customer-details', [BDMProjectController::class, 'customerDetails'])->name('bdm.projects.customer-details');
    //project list

    Route::prefix('bde')->group(function () {
        Route::get('/bde-delete/{id}', [BDMBusinessDevelopmentExcecutiveController::class, 'delete'])->name('bde.delete');
    });
    Route::get('/changeBDEStatus', [BDMBusinessDevelopmentExcecutiveController::class, 'changeBDEStatus'])->name('bde.change-status');
});

//bde routes
Route::group(['middleware' => ['BDE'], 'prefix' => 'bde'], function () {
    Route::get('dashboard', [BDEDashboardController::class, 'index'])->name('bde.dashboard');
    Route::get('profile', [BDEProfileController::class, 'index'])->name('bde.profile');
    Route::post('profile/update', [BDEProfileController::class, 'profileUpdate'])->name('bde.profile.update');
    Route::get('logout', [AuthController::class, 'BusinessDevelopmentManagerlogout'])->name('bde.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [BDEProfileController::class, 'password'])->name('bde.password'); // password change
        Route::post('/update', [BDEProfileController::class, 'passwordUpdate'])->name('bde.password.update'); // password update
    });

    Route::resources([
        'bde-prospects' => BDEProspectController::class,
        'bde-projects' => BDEProjectController::class,
    ]);
    Route::get('/bde-prospects-search', [BDEDashboardController::class, 'bdeDashboardProspectSearch'])->name('bde.dashboard.prospect-search-data');
    Route::get('/filter-prospects', [BDEProspectController::class, 'bdeProspectFilter'])->name('bde.prospects.filter'); // prospect filter
    Route::get('/filter-projects', [BDEProjectController::class, 'bdeProjectFilter'])->name('bde.projects.filter'); // filter


});
