<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\BulkUpload;
use App\Http\Controllers\CommonPoll;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\BookingControllers; 
use App\Http\Controllers\DeveloperControllers;
use App\Http\Controllers\CrmToolsControllers;

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

// Route::get('/logins', function () {
//     return view('welcome');
// });

Auth::routes(); 
Route::group(['middleware' => ['guest']], function(){
Route::post('/login', [AdminController::class, 'login'])->name('login');
Route::get('/', [AdminController::class, 'loginPage'])->name('/');
Route::get('/forgot-password', [AdminController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AdminController::class, 'ForgetPasswordStore'])->name('forgot-password-store');

Route::get('forget-password-send/{token}', [AdminController::class, 'ForgetPasswordSend'])->name('forget-password-send');
Route::post('forget-password-send', [AdminController::class, 'ResetPasswordStore'])->name('ResetPasswordPost');

});

Route::get('/cron-job', [AdminController::class, 'ForACropjob'])->name('cron-job');
Route::get('/create-backup', [AdminController::class, 'createBackup']);
Route::get('/mtd-send-mail', [AdminController::class, 'MTDSendMail']);
Route::get('/mtd-monthly-send-mail', [AdminController::class, 'MTDMonthlyMailReports']);
Route::get('/move-confirm-data', [AdminController::class, 'isLeadConfirmedDataSend'])->name('isDealConfirmDataSend');

Route::get('/clear-cache-all', function() {
  Artisan::call('cache:clear');
  dd("done");
});
 
Route::group(['middleware' => ['auth','lock']], function(){

   

    Route::get('/logout', [AdminController::class, 'logout']);
    
    Route::get('/admin-profile', [AdminController::class, 'adminProfie'])->name('admin-profile');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('update-profile');
    Route::post('change-password', [AdminController::class, 'submitResetPasswordForm'])->name('change-password');
    Route::get('/admin-setting', [AdminController::class, 'adminSetting'])->name('admin-setting');
    Route::post('/setting', [AdminController::class, 'setting'])->name('setting');
    Route::get('/location', [AdminController::class, 'location'])->name('location');
    Route::post('/location-update', [AdminController::class, 'LocationUpdate'])->name('location-update');
    Route::post('/add-location', [AdminController::class, 'CreateLocation'])->name('add-location');
     Route::get('/locationwise/{id}', [AdminController::class, 'locationwise'])->name('locationwise');
     Route::get('/employee-leads/{id}/{l_id}', [AdminController::class, 'EmployeeLead'])->name('employee-leads');
     Route::get('/user-wise-leads/{id}', [AdminController::class, 'UserWiseLeads'])->name('user-wise-leads');
     Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
     Route::post('/status-update/{id}', [AdminController::class, 'statusUpdate'])->name('status-update');
    Route::get('/location-dashboard', [AdminController::class, 'dashboard'])->name('location-dashboard');
    Route::get('/lead-stutas-update/{id}', [AdminController::class, 'LeadStatusUpdate'])->name('lead-stutas-update');
    Route::get('/filter-data', [AdminController::class, 'FilterData'])->name('filter-data');
    Route::get('/employee-leads-show/{id}/{lwls}', [AdminController::class, 'employeeLocationWiseLeadShow'])->name('employee-leads-show');
    Route::get('/lock-screen', [AdminController::class, 'LockScreen'])->name('lock-screen');
    Route::post('lockscreen', [AdminController::class, 'Unlock'])->name('unlock');
    Route::get('/employee-productivity', [AdminController::class, 'Productivity'])->name('employee-productivity');
    Route::get('employee-productivity-status/{id}', [AdminController::class, 'ProductivityStatus'])->name('employee-productivity-status');
    Route::get('employee-productivity-lead/{id}/{status_id}/{filterType}', [AdminController::class, 'ProductivityLeadTable'])->name('employee-productivity-lead');
    Route::get('/get-productivity-data/{employee_id}', 'YourController@getProductivityData')->name('get-productivity-data');
    
    Route::get('/is-location-filter/{id}', [AdminController::class, 'IsLocationFilter'])->name('is-location-filter');
    
    Route::post('/is-employee-filter', [AdminController::class, 'IsEmployeeFilter'])->name('is-employee-filter');
    
    Route::get('notification', [AdminController::class, 'GetNotification'])->name('notification'); 
 
    Route::get('/filter-by-employee', [AdminController::class, 'ProductivityFilterEmployeeStatus'])->name('filter-by-employee');
    
    Route::get('/emp-daywise-prod/{id}/{from}/{to}', [AdminController::class, 'EmployeeDaywiseProd'])->name('emp-daywise-prod');
    
     Route::get('/from-productivity-to', [AdminController::class, 'FromProductivityTo'])
    ->name('fromProductivityTo');

    Route::get('/productivity-filter-highest-to-lowest', [AdminController::class, 'ProductivityFilterHighestToLowest'])
    ->name('productivity-filter-highest-to-lowest');
    
    Route::get('/export-employee-reports/{id}/{from}/{to}', [AdminController::class,'exportEmployeeReports']);

    Route::get('/items/filter', [AdminController::class, 'ProductivityDateFilter'])->name('items.filter');

    Route::get('employee-lead-status/{id}/{status_id}', [AdminController::class, 'employeeLeadStatus'])->name('employee-lead-status');

   Route::get('/our_backup_database', [AdminController::class,'our_backup_database'])
  ->name('our_backup_database');

  Route::get('/download-excel', [AdminController::class, 'LocationDownload'])->name('excel.download');

    Route::get('/trail-logs-filter', [AdminController::class, 'TrailLogsFilter']); 
      Route::get('/trail-logs-excel', [AdminController::class, 'TrailsLogReportsDownload']); 
       Route::get('/generate-csv', [AdminController::class, 'generateCSV']); 
  
   Route::group(['middleware' => ['can:Dashboard']], function () {
      Route::get('/dashboard', [AdminController::class, 'EmployeeDashboard'])->name('dashboard');
     Route::get('employee-location-wise-lead/{id}', [AdminController::class, 'EmployeeLocationWiseLead'])->name('employee-location-wise-lead');
    //  Route::get('/mtd-send-mail', [AdminController::class, 'MTDSendMail']);
    //  Route::get('/mtd-monthly-send-mail', [AdminController::class, 'MTDMonthlyMailReports']);
    //  Route::post('/generate-shortenlink', [AdminController::class,'storeLink'])->name('generate.shorten.link.post');  

    

    //  Route::get('generate-shorten-link',[AdminController::class,'indexLink']);  
    //  Route::get('{code}',[AdminController::class,'shortenLinkFind'])->name('shorten.link');  
     
    });

    Route::get('/call-history/{id}', [AdminController::class, 'CallHistory'])->name('call-history');

 



    // Route::get('/edit-location/{id}', [AdminController::class, 'EditLocation'])->name('edit-location');
   

    // ContactController///////////////////////

    Route::get('/contact', [ContactController::class, 'index'])->name('contact-list'); 
    Route::get('/create-contact', [ContactController::class, 'CreateContactView'])->name('create-contact');
    Route::post('/contact-create', [ContactController::class, 'CreateContact'])->name('contact-create'); 
    Route::get('/edit-contact/{id}', [ContactController::class, 'EditContact'])->name('edit-contact');
    Route::POST('/update-contact/{id}', [ProjectController::class, 'UpdateContact'])->name('update-contact');

    // ProjectController////////////////////////

    Route::group(['middleware' => ['can:Projects']], function () {
    Route::get('/project', [ProjectController::class, 'index'])->name('project-index'); 
    Route::get('/create-project', [ProjectController::class, 'CreateProjectView'])->name('create-project'); 
    Route::post('/project-create', [ProjectController::class, 'CreateProject'])->name('project-create');
    Route::get('/edit-project/{id}', [ProjectController::class, 'EditProject'])->name('edit-project');
    Route::post('/update-project/{id}', [ProjectController::class, 'updateProject'])->name('update-project');
    Route::get('/project-delete/{id}', [ProjectController::class, 'DeleteProject'])->name('project-delete'); 
    Route::get('/search-project', [ProjectController::class, 'SearchProject'])->name('search-project');
    Route::get('/project-details/{id}/{bs_id}', [ProjectController::class, 'BuyerSeller'])->name('project-details');
    Route::get('/project-search', [ProjectController::class, 'ProjectSearch'])->name('project-search');
    Route::get('/emp-wise-customer-type/{id}/{empID}/{bs_id}/', [ProjectController::class, 'customerType'])->name('emp-wise-customer-type');

    
    Route::get('/project-builder-list/{id}', [ProjectController::class, 'ProjectBuilderList'])
    ->name('project-builder-list');
    Route::get('/project-history/{id}', [ProjectController::class, 'ProjectHistory'])->name('project-history');
    });
    Route::get('/project-export/{id}', [ProjectController::class, 'ProjectHistoryExport'])->name('project-export');
    Route::get('/project-export-reports', [ProjectController::class, 'ProjectExportReports'])->name('project-export-reports');

    // LeadsController////////////////////////

      Route::group(['middleware' => ['can:Leads']], function () {
        Route::get('/leads', [LeadsController::class, 'index'])->name('leads-index');
        Route::get('/create-leads', [LeadsController::class, 'createLeadsView'])->name('create-leads'); 
        Route::post('/leads-create', [LeadsController::class, 'createLeads'])->name('leads-create'); 
        Route::get('/edit-leads/{id}', [LeadsController::class, 'EditLeads'])->name('edit-leads');
        Route::POST('/update-lead/{id}', [LeadsController::class, 'updateLead'])->name('update-lead');
        Route::get('/lead-delete/{id}', [LeadsController::class, 'DeleteLead'])->name('lead-delete'); 
        Route::get('/lead-status/{id}', [LeadsController::class, 'LeadStatus'])->name('lead-status'); 
        Route::get('/search-lead', [LeadsController::class, 'SearchLeads'])->name('search-lead'); 
        Route::post('/lead-number', [LeadsController::class, 'RelativeLeadContactNumber'])->name('lead-number'); 
        // Route::get('/notify', [LeadsController::class, 'notifyLead'])->name('notify'); 
        Route::get('/markasread/{id}', [LeadsController::class, 'Markasread'])->name('markasread');
        Route::get('/clear-all-notification', [LeadsController::class, 'ClearNotification'])->name('clear-all-notification');  
        Route::get('/read-single-notification-clear/{id}', [LeadsController::class, 'ReadSingleNotificationClear'])->name('read-single-notification-clear');

        Route::get('/is-leads-number-exist', [LeadsController::class, 'isLeadsNumberExist'])->name('is-leads-number-exist');

        Route::get('/is-send-mail-with-number/{id}', [LeadsController::class, 'isSendMaiWithNumber'])->name('is-send-mail-with-number');

        Route::get('/is-send-mail-without-number/{id}', [LeadsController::class, 'isSendMaiWithoutNumber'])->name('is-send-mail-without-number');
        Route::post('/filter-leads', [LeadsController::class, 'filterLeads'])->name('filter-leads');
        Route::post('/filter-leadsDates', [LeadsController::class, 'filterLeadsDates'])->name('filter-leadsDates');
        Route::get('/free-search', [LeadsController::class, 'FreeSearch'])->name('free-search');
        
        
        // Route::get('/filter-lead', [LeadsController::class, 'SearchLead'])->name('search-lead');
        // Route::get('/lead-info/{id}', [LeadsController::class, 'LeadInfo'])->name('lead-info');
        Route::get('/filter-by-project', [LeadsController::class, 'FilterLeadByProject'])->name('filter-by-project');
        Route::get('/SearchByContact/{id}', [LeadsController::class, 'SearchByContact'])->name('SearchByContact');
        Route::get('/SearchByContactEdit/{l_id}/{id}', [LeadsController::class, 'SearchByContactEdit'])->name('SearchByContactEdit');
        Route::get('/lead-status-isNew/{id}', [LeadsController::class, 'IsLeadCreate'])->name('lead-status-isNew'); 
        Route::get('/lead-status-isHistory/{id}', [LeadsController::class, 'IsLeadHistory'])->name('lead-status-isHistory'); 
        Route::get('/lead-status-isUpdate/{id}', [LeadsController::class, 'IsLeadHistoryUpdate'])->name('lead-status-isUpdate');

        Route::get('/search-by-employee-assign-location/{id}', [LeadsController::class, 'SearchByEmployeeAssignLocation'])->name('search-by-employee-assign-location');
        Route::get('/leads-export', [LeadsController::class, 'LeadExport'])->name('leads-export');
        Route::get('/lead-info-history/{id}', [LeadsController::class, 'LeadInfoHistory'])->name('lead-info-history');
        Route::get('/global-search-details', [LeadsController::class, 'globalSearchDetails'])->name('global-search-details');
        Route::get('/update-session', [LeadsController::class, 'UpdateSessionValue'])
        ->name('update-session');

        Route::get('/employee-lead-export', [LeadsController::class, 'EmployeeReportsDownload'])->name('employee-lead-export');
        
         Route::post('/lead-reopne/{id}', [LeadsController::class, 'LeadReopne'])->name('lead-reopne');
         
          Route::get('/gallary-view/{id}', [LeadsController::class, 'GallaryView'])->name('gallary-view');
        Route::get('/delete-docs/{id}', [LeadsController::class, 'DeleteDocs'])->name('delete-docs');

        Route::get('/lead-search', [LeadsController::class, 'leadSearch'])->name('lead-search');
        
        
         Route::post('/lead-documents-uploade', [LeadsController::class, 'LeadDocumentsUploade'])->name('lead-documents-uploade');
        
      });




    // Bulk-upload Controller////////////////////////
    
    Route::group(['middleware' => ['can:Bulk Upload']], function () {
    Route::get('/bulk-upload', [BulkUpload::class, 'index'])->name('bulk-upload');
    Route::post('/lead-upload', [BulkUpload::class, 'LeadUpload'])->name('lead-upload');
    Route::get('/lead-export', [BulkUpload::class, 'export']);

    Route::post('/checkbox-pool/move-to-pool', [BulkUpload::class, 'moveToPool'])->name('checkbox.move-to-pool');
    Route::post('/bulk-upload-delete', [BulkUpload::class, 'DeleteBulkSheet'])->name('bulk-upload-delete');
    
     Route::post('/bulk-delete-with-no-merge-data', [BulkUpload::class, 'DeleteBulkWithNoMergeData'])->name('bulk-delete-with-no-merge-data');
    
    Route::POST('/bulk-upload-delete-no', [BulkUpload::class, 'BulkUploadDeleteWithNo'])->name('bulk-upload-delete-no');
    Route::POST('/bulk-upload-delete-yes', [BulkUpload::class, 'BulkUploadDeleteWithYes'])->name('bulk-upload-delete-yes');
    Route::get('/bulk-reports-download', [BulkUpload::class, 'BulkReportsDownload'])->name('bulk-reports-download');

    
    });
    
    ////Common Pool Update
    Route::group(['middleware' => ['can:Common Pool']], function () {
    
    ////Common Pool Update
    Route::get('/common-pool', [CommonPoll::class, 'index'])->name('common-pool');
    Route::match(['get', 'post'],'/common-pool-by-filter', [CommonPoll::class, 'FilterLeadByCommonPool'])->name('filter-lead-by-commonpool'); 
    Route::get('/common-pool-filter', [CommonPoll::class, 'LeadFilters'])->name('common-pool-filter');
    Route::post('/employee-assing-comoon-poll', [CommonPoll::class, 'EmployeeAssingComoonPoll'])->name('employee-assing-comoon-poll');
    Route::POST('/assign-common-pool', [CommonPoll::class, 'AssignCommonPool'])->name('assign-common-pool');
      
      Route::get('/common-pool-excel', [CommonPoll::class, 'CommonPoolDownload'])->name('excel.download');
    });
    
    
    // EmployeesController////////////////////////

    Route::group(['middleware' => ['can:Employee']], function () {
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees-index'); 
    Route::get('/employees-detaile/{id}', [EmployeesController::class, 'EmployeeDetails'])->name('employees-detaile');
    Route::post('/create-employee', [EmployeesController::class, 'createEmployee'])->name('create-employee');
    Route::get('/create-employee-view', [EmployeesController::class, 'createEmployeeView'])->name('create-employee-view'); 
    Route::get('/edit-employee/{id}', [EmployeesController::class, 'EditEmployee'])->name('edit-employee');
    Route::post('/update-employee/{id}', [EmployeesController::class, 'updateEmployee'])->name('update-employee');
    Route::get('/employee-delete/{id}', [EmployeesController::class, 'DeleteEmployee'])->name('employee-delete'); 
    Route::get('/search-employee', [EmployeesController::class, 'SearchEmployee'])->name('search-employee');
    Route::get('/employee-export/{id}', [EmployeesController::class, 'EmployeeExport'])->name('employee-export');
    Route::get('/employee-brithday', [EmployeesController::class, 'EmployeeBrithday'])->name('employee-brithday');
    
    Route::get('/user-location', [EmployeesController::class, 'EmployeeLocation'])->name('user-location');
    Route::get('/employee-export', [EmployeesController::class, 'EmployeeExport'])->name('employee-export');
    Route::get('/login-export', [EmployeesController::class, 'LoginReportsDownload'])->name('login-export');
    
    });

    // Builders Controllers///////////////
    Route::group(['middleware' => ['can:Builder/CP Team Names']], function () {
    Route::get('/builder', [BuilderController::class, 'index'])->name('builder-index');
    Route::get('/create-builder', [BuilderController::class, 'CreateBuilder'])->name('create-builder-view');
    Route::post('/builder-create', [BuilderController::class, 'BuilderCreate'])->name('builder-create');
    Route::get('/edit-builder/{id}', [BuilderController::class, 'EditBuilder'])->name('edit-builder');
    Route::post('/update-builder/{id}', [BuilderController::class, 'updateBuilder'])->name('update-builder');
    Route::get('/builder-delete/{id}', [BuilderController::class, 'BuilderDelete'])->name('builder-delete');
    Route::get('/search-by-name-deloper/{id}', [BuilderController::class, 'SearchByNameOfDeloper'])->name('search-by-name-deloper');
    Route::get('builder-details/{id}', [BuilderController::class, 'BuilderDetails'])->name('builder-details');
    Route::get('/builder-excel', [BuilderController::class, 'BuilderReportsDownload'])
    ->name('excel.download');
    });

   

    // RoleAndPermissionController///////////////////////////

    Route::group(['middleware' => ['can:Roles']], function () {
    Route::get('/roles', [RoleAndPermissionController::class, 'index'])->name('role-index');
    Route::get('/role-details/{id}', [RoleAndPermissionController::class, 'DetailsRole'])->name('role-details');  
    Route::get('/roles-create', [RoleAndPermissionController::class, 'roles_create'])->name('roles_create'); 
    Route::POST('/create-role', [RoleAndPermissionController::class, 'CreateRole'])->name('create-roles');
    Route::get('/edit-role/{id}', [RoleAndPermissionController::class, 'EditRole'])->name('edit-role');
    Route::get('/role-delete/{id}', [RoleAndPermissionController::class, 'DeleteRole'])->name('role-delete'); 
    Route::get('/edit-role/{id}', [RoleAndPermissionController::class, 'EditRole'])->name('edit-role'); 
    Route::POST('/update-role/{id}', [RoleAndPermissionController::class, 'updateRole'])->name('update-role');
});

  // booking Confirm Controller
  Route::group(['middleware' => ['can:Booking Confirm']], function () {
  Route::get('booking-confirm/index', [BookingControllers::class, 'index'])->name('booking-index');
  Route::get('/booking-details/{id}', [BookingControllers::class, 'BookingDetails'])->name('booking-details');
  Route::get('/is-booking-cancelled/{id}', [BookingControllers::class, 'isBookingCancelled'])->name('is-booking-cancelled');
  Route::get('/deal-confirm-excel', [BookingControllers::class, 'DealConfirmDownload'])->name('excel.download');
   Route::post('filter-lead-by-booking-confirm', [BookingControllers::class, 'FilterLeadByBookingConfirm'])->name('filter-lead-by-booking-confirm'); 
  });
  
  
    // Developer Controllers 

  // Route::group(['middleware' => ['can:Developers']], function () {
//   Route::get('developer/index', [DeveloperControllers::class, 'index'])->name('developer-index'); 
//   });

  Route::get('developer/index', [DeveloperControllers::class, 'index'])->name('developer-index'); 
  Route::post('/add-developer', [DeveloperControllers::class, 'CreateDeveloper'])->name('add-developer');
  Route::post('/developer-update', [DeveloperControllers::class, 'DeveloperUpdate'])->name('developer-update');
  Route::get('developer-delete/{id}', [DeveloperControllers::class, 'DeveloperDeletes'])->name('developer-delete'); 
  Route::get('/download-excel', [DeveloperControllers::class, 'download'])->name('excel.download');

  Route::get('/crm-toolbar', [CrmToolsControllers::class, 'index'])->name('crm-toolbar');
  Route::get('/crm-toolbar-create', [CrmToolsControllers::class, 'CreateIndex'])->name('crm-toolbar-create');
  Route::post('/toolbar-create', [CrmToolsControllers::class, 'CreateCrmTool'])->name('toolbar-create');
  Route::get('/toolbar-edit/{id}', [CrmToolsControllers::class, 'editCrmTool'])->name('toolbar-edit');
  Route::post('/update-toolbar/{id}', [CrmToolsControllers::class, 'UpdateCrmTollProject'])->name('update-toolbar');
  Route::get('/delte-toolbar/{id}', [CrmToolsControllers::class, 'CrmTollDelete'])->name('delte-toolbar');
  
  
});

