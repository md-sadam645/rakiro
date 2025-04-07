<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailComposeController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\SmtpSetupController;
use App\Http\Controllers\Admin\CreateListController;
use App\Http\Controllers\Admin\CommonSetupController;
use App\Http\Controllers\Admin\AutomailController;


//AUTH ROUTES STARTS
Route::get('/',[LoginController::class,'adminlogin']);
Route::post('adminauth',[LoginController::class,'authadmin'])->name('adminvalidation');
Route::get('unauthorized_access',[LoginController::class,'unauthorized']);
Route::get('email-schedule',[EmailComposeController::class,'schedule']);
Route::get('email-automation',[AutomailController::class,'automation']);
Route::get('queue-work',[DashboardController::class,'queueWork']);
Route::get('send-failed-work',[DashboardController::class,'failedWork']);
//AUTH ROUTES ENDS

//ADMIN ROUTES STARTS
Route::group(['middleware'=>['IsAdmin']],function()
{
    //admin ROUTES
    Route::get('sub-admin/view',[SubAdminController::class,'index']);
    Route::get('sub-admin/create',[SubAdminController::class,'add']);
    Route::post('subAdmin/save',[SubAdminController::class,'save']);
    Route::get('sub-admin/edit/{id}',[SubAdminController::class,'edit']);
    Route::post('subAdmin/update/{id}',[SubAdminController::class,'update']);
    Route::get('sub-admin/delete/{id}',[SubAdminController::class,'destroy']);

    //Smtp-setup ROUTES
    Route::get('smtp-setup/create',[SmtpSetupController::class,'index']);
    Route::Post('smtp-setup/add',[SmtpSetupController::class,'add']);
    Route::get('smtp-setup/view',[SmtpSetupController::class,'view']);
    Route::get('smtp-setup/edit/{id}',[SmtpSetupController::class,'edit']);
    Route::Post('smtp-setup/update/{id}',[SmtpSetupController::class,'update']);
    Route::get('smtp-setup/delete/{id}',[SmtpSetupController::class,'delete']);

    //Common-setup ROUTES
    Route::get('common-setup/create',[CommonSetupController::class,'index']);
    Route::Post('common-setup/update',[CommonSetupController::class,'update']);
});
//ADMIN ROUTES ENDS


//IsAdminAndSubAdmin ROUTES STARTS
Route::group(['middleware'=>['IsAdminAndSubAdmin']],function()
{
    //DASHBOARD ROUTES
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    Route::Post('smtp-setup/config',[SmtpSetupController::class,'smtpSetup']);
    
    //create-list ROUTES
    Route::get('create-list/create',[CreateListController::class,'index']);
    Route::get('create-list/filter',[CreateListController::class,'save']);
    // Route::get('/state/{name}',[CreateListController::class,'state']);
    // Route::get('/city/{id}',[CreateListController::class,'city']);
    Route::get('pincode/{pincode}',[CreateListController::class,'pincode']);
    Route::Post('createGroup',[CreateListController::class,'createGroup']);
    Route::get('create-list/view',[CreateListController::class,'groupList']);
    Route::get('create-list/groupDetails',[CreateListController::class,'getGroupData']);
    Route::get('create-list/detail',[CreateListController::class,'detail']);
    Route::Post('create-list/duplicate',[CreateListController::class,'duplicate']);
    Route::get('create-list/sync/{id}',[CreateListController::class,'sync']);
    Route::post('update-email/{id}',[CreateListController::class,'emailUpdate']);
    Route::get('create-list/delete/{id}',[CreateListController::class,'delete']);

    //email-compose ROUTES
    Route::get('email-compose/create',[EmailComposeController::class,'index']);
    Route::Post('emailCompose',[EmailComposeController::class,'add']);
    Route::get('email-compose/view',[EmailComposeController::class,'view']);
    Route::get('email-compose/edit/{id}',[EmailComposeController::class,'edit']);
    Route::Post('email-compose/update/{id}',[EmailComposeController::class,'update']);
    Route::get('email-compose/duplicate/{id}',[EmailComposeController::class,'duplicate']);
    Route::Post('email-compose/duplicate-update/{id}',[EmailComposeController::class,'updateDuplicate']);
    Route::get('email-compose/delete/{id}',[EmailComposeController::class,'delete']);
    Route::get('email-compose/attch/delete',[EmailComposeController::class,'attachDelete']);
    Route::get('email-compose/attch/empty',[EmailComposeController::class,'attachEmpty']);
    Route::get('email-form-reset/{id}',[EmailComposeController::class,'formReset']);
    Route::get('email-details/{id}',[EmailComposeController::class,'sentDetails']);
    // Route::get('email-test-schedule',[EmailComposeController::class,'testEmail']);

    // Stop sending email
    // Route::get('stop-sending-email/{id}',[EmailComposeController::class,'stopEmail']);
    // Route::get('start-sending-email/{id}',[EmailComposeController::class,'startEmail']);

    //email-compose ROUTES
    Route::Get('automail/create',[AutomailController::class,'index']);
    // Route::get('/automation',[AutomailController::class,'automation']);
    Route::Post('automail/add',[AutomailController::class,'add']);
    Route::get('automail/view',[AutomailController::class,'view']);
    // Route::get('test/automail',[AutomailController::class,'test_automail']);
    Route::get('automail/edit/{id}',[AutomailController::class,'edit']);
    Route::Post('automail/update/{id}',[AutomailController::class,'update']);
    Route::get('automail/status/{id}',[AutomailController::class,'status']);
    Route::get('automail/delete/{id}',[AutomailController::class,'delete']);
    Route::get('automail/attch/delete',[AutomailController::class,'attachDelete']);
    Route::get('automail/attch/empty',[AutomailController::class,'attachEmpty']);
    Route::get('automail-form-reset/{id}',[AutomailController::class,'formReset']);
    Route::get('automail-details/{id}',[AutomailController::class,'automailSentDetails']);

    //profile ROUTES
    Route::get('logout_admin',[LoginController::class,'logout_admin']);
    Route::get('profile/view',[LoginController::class,'profile']);
    Route::post('profile/update',[LoginController::class,'profileUpdate']);
    Route::get('change-password',[LoginController::class,'changePassword']);
    Route::post('updatePassword',[LoginController::class,'updatePassword']);
});
//IsAdminAndSubAdmin ROUTES ENDS

