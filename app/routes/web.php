<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});
Route::post('/upload', 'ApiController@upload');
Route::get('uploadusers', 'ApiController@uploadusers')->name('users.upload');
//API
Route::post('/districts/get', 'ApiController@districts');
Route::post('/api/login', 'Api2Controller@login');
Route::get('/api', 'Api2Controller@index');
Route::post('/api/products', 'Api2Controller@products');
Route::post('/api/addcart', 'Api2Controller@addcart');
Route::post('/api/checkout', 'Api2Controller@checkout');
Route::post('/api/getcart', 'Api2Controller@getcart');
Route::post('/api/orders', 'Api2Controller@orders');
Route::post('/api/deleteCartItem', 'Api2Controller@deleteCartItem');
//field report
Route::post('/api/fieldreport', 'Api2Controller@reportError');
Route::post('/api/farminput', 'Api2Controller@farminput');





Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('user.logout');
        Route::post('admin/signup', 'LoginController@admincreate')->name('signup');
        Route::get('admin/confirm/{token}', 'LoginController@confirm')->name('confirm');
       
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications','AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all','AdminController@readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report','AdminController@requestReport')->name('request.report');
        Route::post('request-report','AdminController@reportSubmit');

        Route::get('system-info','AdminController@systemInfo')->name('system.info');


        // Users Manager
        //create tenant
        Route::get('tenant/create', 'ManageUsersController@addTenant')->name('users.addtenant');
        Route::post('tenant/save', 'ManageUsersController@tenantcreate')->name('users.savetenant');
        Route::post('tenant/remove', 'ManageUsersController@delete')->name('users.delete');
        Route::post('tenant/removeadmin', 'ManageUsersController@deleteadmin')->name('users.deleteadmin');

        Route::get('admin/reset/{id}', 'ManageUsersController@reset')->name('users.reset');
        Route::get('users', 'ManageUsersController@allUsers')->name('tenants.all');
        Route::get('admins', 'ManageUsersController@allAdmins')->name('users.admins');
        Route::get('admin/create', 'ManageUsersController@addAdmins')->name('users.addadmin');
        Route::post('admin/save', 'ManageUsersController@admincreate')->name('users.admincreate');
        Route::get('admin/detail/{id}', 'ManageUsersController@admindetail')->name('users.admindetail');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('tenants.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('users/{scope}/search.admins', 'ManageUsersController@adminsearch')->name('users.adminsearch');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('admin/update', 'ManageUsersController@adminupdate')->name('users.adminupdate');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.add.sub.balance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('admin/login/{id}', 'ManageUsersController@adminlogin')->name('users.adminlogin');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

       

        Route::name('supportticket.')->prefix('manage')->group(function(){
            Route::get('mytickets', 'SupportTicketController@createtickets')->name('create');
            Route::get('/supportticketsreturn', 'SupportTicketController@supportTicketlegacy')->name('support_ticket');
    Route::get('/new', 'SupportTicketController@openSupportTicket')->name('open');
    Route::post('/create', 'SupportTicketController@storeSupportTicket')->name('store');
    Route::get('/viewmyticket/{ticket}', 'SupportTicketController@viewTicketuser')->name('view');
    Route::post('/reply/{ticket}', 'SupportTicketController@replyTicketuser')->name('reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('download');
          
        });
      
          // Admin Support
          Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
          Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
          Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
          Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
          Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
          Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
          Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
          Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');



        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css','GeneralSettingController@customCssSubmit');


        //Cookie
        Route::get('cookie','GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie','GeneralSettingController@cookieSubmit');


        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');

        //estate here
        Route::name('estate.')->prefix('manage')->group(function(){
            //fleet type
            Route::get('estates', 'EstateController@list')->name('list');
            Route::get('types', 'EstateController@types')->name('types');
            Route::post('estate/type/store', 'EstateController@storetype')->name('storetype');
            Route::post('estate/type/delete', 'EstateController@deletetype')->name('deletetype');
            Route::post('estate/updatetype/{id}', 'EstateController@updatetype')->name('updatetype');
          
          
            Route::get('companies', 'CompanyController@list')->name('companies');
            Route::post('estate/company/store', 'CompanyController@store')->name('storecompany');
            Route::post('estate/company/delete', 'CompanyController@delete')->name('deletecompany');
            Route::post('estate/companyupdate/{id}', 'CompanyController@update')->name('updatecompany');
          

           Route::post('estate', 'EstateController@store')->name('store');
            Route::post('estate/update/{id}', 'EstateController@update')->name('update');
            Route::post('estate/active-disable', 'EstateController@EnableDisabled')->name('disable');

       });




       //departments
               Route::name('department.')->prefix('department')->group(function(){
                //fleet type
                Route::get('list', 'DepartmentController@list')->name('list');
                Route::post('store', 'DepartmentController@store')->name('store');
                Route::post('dept/type/delete', 'DepartmentController@deletetype')->name('delete');
                Route::post('dept/updatetype/{id}', 'DepartmentController@updatetype')->name('update');
                Route::post('dept/active-disable', 'DepartmentController@EnableDisabled')->name('disable');
              
              
           });

           //warehousing
           Route::name('warehousing.')->prefix('warehousing')->group(function(){
            //fleet type
            Route::get('list', 'WarehouseController@list')->name('list');
            Route::post('store', 'WarehouseController@store')->name('store');
            Route::post('update/{id}', 'WarehouseController@update')->name('update');
            Route::post('delete', 'WarehouseController@EnableDisabled')->name('delete');
            Route::get('stock/create', 'WarehouseController@stocktaking')->name('stock');
            Route::post('stock/store', 'WarehouseController@storestock')->name('stock.store');
           
          
          
       });

          //printing
          Route::name('jobcard.')->prefix('jobcard')->group(function(){
            Route::get('print/{id}', 'JobcardController@print')->name('print');
            
          
       });

           

                //departments
                Route::name('directorates.')->prefix('directorates')->group(function(){
                    //fleet type
                    Route::get('list', 'DirectorateController@list')->name('list');
                    Route::post('store', 'DirectorateController@store')->name('store');
                    Route::post('div/type/delete', 'DirectorateController@deletetype')->name('delete');
                    Route::post('div/updatetype/{id}', 'DirectorateController@updatetype')->name('update');
                    Route::post('div/active-disable', 'DirectorateController@EnableDisabled')->name('disable');
                  
                  
               });




                      //farms
                      Route::name('farms.')->prefix('farms')->group(function(){
                        //fleet type
                        Route::get('list', 'FarmController@list')->name('list');
                        Route::post('store', 'FarmController@store')->name('store');
                        Route::post('farm/delete', 'FarmController@delete')->name('delete');
                        Route::post('farm/{id}', 'FarmController@update')->name('update');
                        Route::post('farm/active-disable', 'FarmController@EnableDisabled')->name('disable');
                        Route::get('map', 'FarmController@maps')->name('maps'); 
                      
                   });




               //vehicles

               Route::name('vehicles.')->prefix('vehicles')->group(function(){
                Route::get('upload', 'VehicleController@upload')->name('upload');
                //makes
                Route::get('makes', 'ManageFleetController@make')->name('makes');
                Route::post('make/store', 'ManageFleetController@storemake')->name('make.store');
                Route::post('make/updatetype/{id}', 'ManageFleetController@updatemake')->name('make.update');
                Route::post('make/active-disable', 'ManageFleetController@EnableDisabled')->name('make.disable');
              

                //models
                Route::get('models', 'ModelsController@list')->name('models');
                Route::post('models/store', 'ModelsController@store')->name('model.store');
                Route::post('models/updatetype/{id}', 'ModelsController@update')->name('model.update');
                Route::post('models/active-disable', 'ModelsController@EnableDisabled')->name('model.disable');


                 //types
                 Route::get('types', 'TypeController@list')->name('types');
                 Route::post('types/store', 'TypeController@store')->name('type.store');
                 Route::post('types/updatetype/{id}', 'TypeController@update')->name('type.update');
                 Route::post('types/active-disable', 'TypeController@EnableDisabled')->name('type.disable');

                 //checklists

                 Route::get('checklists', 'ChecklistController@list')->name('checklists');
                 Route::post('checklists/store', 'ChecklistController@store')->name('checklist.store');
                 Route::post('checklists/updatetype/{id}', 'ChecklistController@update')->name('checklist.update');
                 Route::post('checklists/active-disable', 'ChecklistController@EnableDisabled')->name('checklist.disable');

                 Route::get('checklists/types', 'ChecklistController@types')->name('checklist.types');
                 Route::post('checkliststype/store', 'ChecklistController@storetype')->name('checklisttype.store');
                 Route::post('checklisttype/updatetype/{id}', 'ChecklistController@updatetype')->name('checklisttype.update');
                 Route::post('checklisttype/active-disable', 'ChecklistController@EnableDisabledtype')->name('checklisttype.disable');

                 //lets go to cars
                 Route::get('equipments', 'VehicleController@list')->name('list');
                 Route::get('new/equipment', 'VehicleController@addCar')->name('addcar');
                 Route::post('vehicles/store', 'VehicleController@storecar')->name('savevehicle');
                 Route::post('vehicle/{id}', 'VehicleController@update')->name('update');
                 Route::get('vehicle/{id}', 'VehicleController@details')->name('detail');
                 Route::post('vehicle/delete', 'VehicleController@delete')->name('delete');


         
           });


            //crm
            Route::name('repair.')->prefix('repairs')->group(function(){
                Route::get('jobcard/{id}', 'RepairController@jobcard')->name('jobcard');
              
                Route::get('create', 'RepairController@create')->name('create');
                Route::post('store', 'RepairController@store')->name('store');
                Route::post('storedraft', 'RepairController@storedraft')->name('storedraft');
                
                Route::get('list', 'RepairController@list')->name('list');
                Route::get('jobcards', 'RepairController@jobcards')->name('jobcards');

                

                Route::post('repair/delete', 'RepairController@delete')->name('delete');
                Route::post('repair/diognosis/{id}', 'RepairController@diognosis')->name('updatediognosis');
                Route::post('repair/approvespare/{id}', 'RepairController@approvespares')->name('approvespares');
                Route::post('repair/workdone/{id}', 'RepairController@workdone')->name('workdone');
                Route::post('repair/issuestock/{id}', 'RepairController@issuestock')->name('issue');
                Route::post('repair/stockreturned/{id}', 'RepairController@stockreturned')->name('returned');
                Route::get('repair/{id}', 'RepairController@details')->name('detail');
                Route::post('repair/approvedefects/{id}', 'RepairController@approvedefects')->name('approvedefects');
                Route::post('repair/test/{id}', 'RepairController@Test')->name('test');
                Route::post('repair/certify/{id}', 'RepairController@certify')->name('certify');

                
               
                //logs
                Route::get('logs/{id}', 'RepairController@logs')->name('logs');

                        
           });



       //deal with subscription
            Route::name('subscription.')->prefix('subscription')->group(function(){
                Route::get('list', 'SubscriptionController@list')->name('list');
                Route::post('store', 'SubscriptionController@store')->name('store');
                        
           });


       //units
       Route::name('units.')->prefix('manage')->group(function(){
        //fleet type
        Route::get('units/manage/{id}', 'UnitsController@manage')->name('manage');
        Route::get('units', 'UnitsController@list')->name('list');
        Route::post('units', 'UnitsController@store')->name('store');
        Route::post('units/upd', 'UnitsController@updatemanage')->name('updatemanage');
        Route::post('units/update/{id}', 'UnitsController@update')->name('update');
        Route::post('units/active-disable', 'UnitsController@EnableDisabled')->name('disable');
   });

          //requests
            Route::name('drugs.')->prefix('drugs')->group(function(){
            Route::get('new', 'DrugsController@list')->name('list');
            Route::get('list', 'DrugsController@sent')->name('sent');
            Route::post('units', 'DrugsController@store')->name('store');
            Route::post('units/update/{id}', 'DrugsController@update')->name('update');
            Route::post('units/active-disable', 'DrugsController@EnableDisabled')->name('disable');
       });

       //requisitions
       Route::name('requisitions.')->prefix('requisitions')->group(function(){
         Route::get('list', 'RequisitionController@list')->name('list');
         Route::post('requisition/update/{id}', 'RequisitionController@update')->name('update');

         Route::get('details/{id}', 'RequisitionController@details')->name('detail');



        });


   //expense
   Route::name('expenses.')->prefix('expenses')->group(function(){
    //fleet type
    Route::get('types', 'ExpenseController@listtypes')->name('types');
    Route::post('types/store', 'ExpenseController@storetype')->name('storetype');
    Route::get('all', 'ExpenseController@list')->name('list');
    Route::post('store', 'ExpenseController@store')->name('store');
    Route::post('expense/update/{id}', 'ExpenseController@update')->name('update');
    Route::post('expense/remove', 'ExpenseController@remove')->name('remove');
    Route::post('expenses/type-disable', 'ExpenseController@deleteType')->name('disabletype');
    /*Route::post('units', 'UnitsController@store')->name('store');
    Route::post('units/upd', 'UnitsController@updatemanage')->name('updatemanage');
    Route::post('units/active-disable', 'UnitsController@EnableDisabled')->name('disable');*/
});

//products
Route::name('products.')->prefix('products')->group(function(){
    Route::get('categories', 'ProductController@listtypes')->name('categories');
    Route::post('category/store', 'ProductController@storecategory')->name('storecategory');
    Route::post('store/category/{id}', 'ProductController@editCategory')->name('update.category');
    Route::post('category/remove', 'ProductController@deleteCategory')->name('deletecategory');
    Route::get('all', 'ProductController@list')->name('all');
    Route::post('store', 'ProductController@store')->name('store');
    Route::post('update/{id}', 'ProductController@update')->name('update');
    Route::post('product/remove', 'ProductController@remove')->name('remove');
    /*Route::get('all', 'ExpenseController@list')->name('list');
    Route::post('store', 'ExpenseController@store')->name('store');
    Route::post('expense/update/{id}', 'ExpenseController@update')->name('update');
    Route::post('expense/remove', 'ExpenseController@remove')->name('remove');
    Route::post('expenses/type-disable', 'ExpenseController@deleteType')->name('disabletype');*/
    /*Route::post('units', 'UnitsController@store')->name('store');
    Route::post('units/active-disable', 'UnitsController@EnableDisabled')->name('disable');*/
});

//pos
Route::name('pos.')->prefix('pos')->group(function(){
    Route::get('all/{id}', 'PosController@home')->name('home');
    Route::get('roomservice/{id}', 'PosController@home')->name('roomservice');
    Route::post('add', 'PosController@cart')->name('add');
    
    Route::post('delete/cart', 'PosController@cartdelete')->name('deletecart');
    //checking
    Route::get('checking', 'CheckinController@checking')->name('checking');
    Route::post('checking/store', 'CheckinController@store')->name('checkingstore');
    Route::post('checking/delete', 'CheckinController@delete')->name('removechecking');
    Route::post('checkout', 'PosController@checkout')->name('checkout');
   
    
    Route::post('checking/update/{id}', 'CheckinController@update')->name('updatecheckin');
    
});

   //invoice
   Route::name('invoice.')->prefix('invoices')->group(function(){
    Route::get('all', 'InvoiceController@all')->name('list');
    Route::get('search', 'InvoiceController@all')->name('search');
    Route::post('store', 'InvoiceController@store')->name('store');
    Route::post('approve', 'InvoiceController@pay')->name('pay');
    Route::get('create', 'InvoiceController@create')->name('create');
    Route::get('datesearch', 'InvoiceController@all')->name('datesearch');
    Route::get('details/{id}', 'InvoiceController@details')->name('details');
    Route::post('reject', 'InvoiceController@reject')->name('reject');
    Route::post('invoice/delete', 'InvoiceController@delete')->name('delete');
   /* Route::post('units', 'UnitsController@store')->name('store');
    Route::post('units/upd', 'UnitsController@updatemanage')->name('updatemanage');
    Route::post('units/update/{id}', 'UnitsController@update')->name('update');
    Route::post('units/active-disable', 'UnitsController@EnableDisabled')->name('disable');*/
});

   //reports
   Route::name('reports.')->prefix('reports')->group(function(){
    Route::get('pendingjobs', 'ReportController@pendingjobs')->name('pending');
    Route::get('pending/status', 'ReportController@pending_per_status')->name('pending_status');
    Route::get('warehouse/stock', 'ReportController@warehousebalance')->name('warehousebalance');
    Route::post('warehouse/stock', 'ReportController@warehousebalance')->name('warehousebalance');

    
    Route::get('product/stock', 'ReportController@productbalance')->name('productbalance');
    Route::post('product/stock', 'ReportController@productbalance')->name('productbalance');

    Route::get('service/tracker', 'ReportController@service_tracker')->name('service_tracker');


   // Route::get('tenants', 'ReportController@tenants')->name('tenants');
   });

   //sms
   Route::name('sms.')->prefix('sms')->group(function(){
    Route::get('sms', 'SmsTemplateController@index')->name('template.index');
    Route::get('sms/{id}', 'SmsTemplateController@edit')->name('template.edit');
    Route::post('sms/template/{id}', 'SmsTemplateController@update')->name('template.update');

    Route::post('sms/template/save', 'SmsTemplateController@update')->name('template.setting');
   
    Route::get('settings', 'SmsTemplateController@smsSetting')->name('template.smsSettingload');
    Route::post('settings/save', 'SmsTemplateController@smsSettingUpdate')->name('template.smsSetting');

    Route::get('templates', 'SmsTemplateController@smsTemplate')->name('templates.create');
    Route::post('templates/global', 'SmsTemplateController@smsTemplate')->name('templates.global');
    Route::get('create', 'SmsTemplateController@createtemplate')->name('template.create');
    Route::post('storetemplate', 'SmsTemplateController@storetemplete')->name('template.store');

    //send sms here
    Route::get('send', 'SmsTemplateController@sendIndex')->name('template.send.index');
    Route::post('sendsms', 'SmsTemplateController@sendsms')->name('template.sms.send');
    
   
  //  Route::get('sms/send', 'SmsController@tenants')->name('send');

   });





    });
});

Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');

            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');

            //ticket
            Route::get('booked-ticket/history', 'UserController@ticketHistory')->name('ticket.history');
            Route::get('booked-ticket/print/{id}', 'UserController@printTicket')->name('ticket.print');


            //invoices
            Route::get('invoice-history', 'UserController@invoices')->name('invoices.history');
          



            // Deposit //payment ticket booking
            Route::any('/ticket-booking/payment-gateway', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::get('/ticket-booking/payment-gateway/resume/{pnr_number}', 'Gateway\PaymentController@depositresume')
            ->name('deposit.resume');
            Route::post('/ticket-booking/pay', 'Gateway\PaymentController@pay')->name('mobile.money');
            Route::post('/ticket-booking/paynow', 'Gateway\PaymentController@paynow')->name('pay.now');
            
            Route::post('ticket-booking/payment/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('ticket-booking/payment/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('ticket-booking/payment/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('ticket-booking/payment/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('ticket-booking/payment/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
        });
    });
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('support_ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});








    Route::post('forgot', 'ApiController@forgot')->name('forgot');
    Route::post('confirmotp', 'ApiController@confirmotp')->name('confirmotp');
    Route::post('resetpassword', 'ApiController@resetpassword')->name('resetpassword');
    
    //bus work
      Route::post('locations', 'ApiController@counters')->name('locations');
      Route::get('trips', 'ApiController@trips')->name('trips');
      Route::post('trips', 'ApiController@trips')->name('trips');  
       //booking route
  Route::get('/', 'SiteController@index');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');
  
 


Route::get('/units/{division}', 'ApiController@getUnits')->name('ajax.units');
Route::get('/models/{division}', 'ApiController@getModels')->name('ajax.models');
Route::get('book/search','SiteController@booksearch')->name('search');
Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/blog', 'SiteController@blog')->name('blog');
Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');
Route::get('policy/{id}/{slug}', 'SiteController@policyDetails')->name('policy.details');
Route::get('cookie/details', 'SiteController@cookieDetails')->name('cookie.details');
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');
Route::get('/tickets', 'SiteController@ticket')->name('ticket');
Route::get('/', 'SiteController@index')->name('home');












