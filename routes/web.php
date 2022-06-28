<?php

use App\Helpers\ActivityConfig;
use App\Http\Controllers\ExportTC;
use Illuminate\Support\Facades\DB;

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


Route::group(['middleware' => 'checkConfig'], function () {

    Route::get("/discord/login", "Auth\LoginController@discord")->name("discord.login");
    Route::get('/discord/redirect', "Auth\LoginController@discordRedirect")->name("discord.redirect");

    Route::group(['middleware' => 'auth'], function (){
        Route::get('/', function () {
            return view('home');
        })->name("home");
        
        Route::get("/user-profile/{id}", 'HomeController@user_profile')->name("home.user.profile");

        Route::get('/menu/list', 'HomeController@menu_list')->name('menu.list');
        Route::post('/menu/post', 'HomeController@menu_redirect')->name('menu.direct');
        Route::get('/notification/{type?}/{id?}', 'HomeController@notif_clear')->name('notif.clear');

        Route::post("/reset-daily", "HomeController@reset_daily")->name("home.reset");

        Route::post('/daily-seed', "HomeController@daily_seed")->name("home.daily.seed");

        Route::post("/generate-char", "HomeController@generate_char")->name("home.generate.char");
        Route::post("/find-char", "HomeController@find_char")->name("home.find.char");
        Route::post("/hire-char", "HomeController@hire_char")->name("home.hire.char");
        Route::post("/dismiss-char", "HomeController@dismiss_char")->name("home.dismiss.char");
        Route::post("/pay-char", "HomeController@pay_char")->name("home.pay.char");
        Route::post("/list-char", "HomeController@list_char")->name("home.list.char");
        Route::post("/assign-char", "HomeController@assign_char")->name("home.assign.char");
        Route::post('/train-char', "HomeController@train_char")->name("home.train.char");

        Route::post("/hunt", "HomeController@hunt")->name("home.hunt");
        Route::post('/hunt-done', "HomeController@hunt_done")->name("home.hunt.done");

        Route::post('/buy-caravan', "HomeController@buy_caravan")->name("home.caravan.buy");
        Route::get("/trip-list-town", "HomeController@town_caravan")->name("home.caravan.town");
        Route::post("/trip-confirmation", "HomeController@town_confirmation_caravan")->name("home.caravan.town_confirmation");
        Route::get('/trip-countdown', "HomeController@trip_countdown")->name("home.trip.countdown");
        Route::post('/trip-check-in', "HomeController@trip_checkin")->name("home.trip.checkin");

        Route::get("/list-city", "HomeController@list_city")->name("home.city");
        Route::get("/open-city/{id?}", "HomeController@open_city")->name("home.city.open");
        Route::post("/store-city", "HomeController@store_city")->name("home.city.store");
        Route::get("/user-city", "HomeController@user_city")->name("home.city.user");
        Route::post('/city-visit', "HomeController@visit_city")->name("home.city.visit");
        Route::post("/city-home", "HomeController@home_city")->name("home.city.back");
        Route::post('/city-move', "HomeController@move_city")->name("home.city.move");

        Route::get("/list-plants", "HomeController@list_plants")->name("home.plants");
        Route::get("/table-plants", "HomeController@table_plants")->name("home.plants.table");
        Route::get("/table-seeds", "HomeController@table_seeds")->name("home.seeds.table");
        Route::get("/farmed-plant/{id?}", "HomeController@farmed_plant")->name("home.plants.farmed");
        Route::get("/sell-modal-plant/{id?}", "HomeController@sell_form_plant")->name("home.plants.sell_form");
        Route::post('/select-plant', "HomeController@select_plants")->name("home.plants.select");
        Route::get("/countdown-plants", "HomeController@countdown_plants")->name("home.plants.countdown");
        Route::post('/farm-plant', "HomeController@farm_plant")->name("home.plants.farm");
        Route::post('/sell-confirmation', "HomeController@sell_confirmation")->name("home.sell.confirmation");
        Route::post('/sell-plant', "HomeController@sell_plant")->name("home.sell.plant");
        Route::post("/check-balance", "HomeController@check_balance")->name("home.balance.check");
        Route::post("/buy-beer", "HomeController@buy_beer")->name("home.beer.buy");

        // rumors
        Route::get("/get-rumors", "HomeController@get_rumors")->name("home.rumors.get");
        Route::get("/share-rumor", "HomeController@share_rumors")->name("home.rumors.share");
        // event
        Route::get("/event", "HomeController@trigger_event")->name("home.event.trigger");

        Route::get('/activity/log', 'HomeController@activity_log')->name('activity.log');

        Route::post("/user/update-metamask", "UsersController@update_metamask")->name("update.metamask");
        Route::post("/user/check-metamask", "UsersController@check_metamask")->name("check.metamask");

        Route::get('/access-denied', function(){
            return view('errors.access');
        })->name('access.denied');

        // Route::get('/clear-cache', function() {
        //     $exitCode = Artisan::call('cache:clear');
        //     // return what you want
        //     return redirect()->back();
        // });
        //account info
         Route::get('/account/info/{id}','UsersController@getDetailUser')->name('account.info');
         Route::post("/account/attend-code/randomize", 'UsersController@randomize')->name('account.randomize');
        Route::post('/user/sign/add/{id}', 'UsersController@signAdd')->name('account.sign.add');
        Route::post('/account/update/password','UsersController@updatePasswordAccount')->name('account.update.password');
        Route::post('/account/update/detail','UsersController@updateAccountInfo')->name('account.update.info');
        //company
        Route::get('/company', 'CompanyController@index')->name('company.index');
        Route::post('company/add', 'CompanyController@add')->name('company.add');
        Route::get('/company/switch', 'CompanyController@switch')->name('company.switch');
        Route::get('/company/detail/{id}', 'CompanyController@detail')->name('company.detail');
        Route::get('/company/user/{id}', 'CompanyController@comp_user')->name('company.user');
        Route::post('/company/delete', 'CompanyController@delete')->name('company.delete');
        Route::post('/company/edit', 'CompanyController@edit')->name('company.edit');
        Route::get('/company/role-control/{id}', 'CompanyController@role_controll')->name('company.role_controll');
        Route::post('/company/generate_code','CompanyController@generate_code')->name('company.generate_code');

        //User
        Route::post('/user/add', 'UsersController@add')->name('user.add');
        Route::get('/user/delete/{id?}', 'UsersController@delete')->name('user.delete');
        Route::post('/user/edit', 'UsersController@edit')->name('user.edit');
        Route::get('/user/{id}/privilege','UsersController@getUserPrivilege')->name('user.privilege');
        Route::post('/uprivilege/{id}/edit', 'UsersController@updatePrivilege')->name('user.uprivilege');
        Route::get('/user/getuser/{id_company}','UsersController@getUsers')->name('user.getUsers');
        Route::get('/user/getcompany_name','UsersController@getCompany')->name('user.getCompany');
        Route::get('/user/inherit-position/{id?}','UsersController@inherit')->name('user.inherit');
        Route::post('/user/privilege/module', 'UsersController@userModule')->name('user.priv.module');
        Route::post('/user/privilege/module/save', 'UsersController@userModuleSave')->name('user.priv.module.save');
        Route::get('/user/my-zakat', 'UsersController@my_zakat')->name('user.my.zakat');
        Route::post('/user/my-zakat', 'UsersController@pay_zakat')->name('user.pay.zakat');


        //Division
        // Route::get('/division', 'DivisionController@index')->name('division.index');
        Route::post('/division', 'DivisionController@store')->name('division.store');
        Route::post('/division/{id}/update', 'DivisionController@update')->name('division.update');
        Route::delete('/division/{id}/delete', 'DivisionController@delete')->name('division.delete');
        Route::get('/division/lists', 'DivisionController@list_js')->name('division.js');

        //Role
        // Route::get('/role', 'RoleController@index')->name('role.index');
        Route::post('/role', 'RoleController@store')->name('role.store');
        Route::post('/role/{id}/update', 'RoleController@update')->name('role.update');
        Route::delete('/role/{id}/delete', 'RoleController@delete')->name('role.delete');

        //Role Division (Position)
        // Route::get('/rolediv', 'RoleDivController@index')->name('rolediv.index');
        Route::post('/rolediv', 'RoleDivController@store')->name('rolediv.store');
        Route::post('/rolediv/{id}/update', 'RoleDivController@update')->name('rolediv.update');
        Route::delete('/rolediv/{id}/delete', 'RoleDivController@delete')->name('rolediv.delete');
        Route::get('/rprivilege/{id}/edit', 'RoleDivController@editPrivilege')->name('rprivilege.edit');
        Route::post('/rprivilege/{id}/edit', 'RoleDivController@updatePrivilege')->name('rprivilege.update');

        //Module
        // Route::get('/module', 'ModuleController@index')->name('module.index');
        Route::post('/module', 'ModuleController@store')->name('module.store');
        Route::post('/module/{id}/update', 'ModuleController@update')->name('module.update');
        Route::delete('/module/{id}/delete', 'ModuleController@delete')->name('module.delete');

        //employee type
        Route::post('/role-control/employee-type', 'HrdEmployeeTypeController@store')->name('rc.emp.store');
        Route::post('/role-control/employee-type/update/{id}', 'HrdEmployeeTypeController@update')->name('rc.emp.update');
        Route::delete('/role-control/employee-type/delete/{id}', 'HrdEmployeeTypeController@delete')->name('rc.emp.delete');

        //Action
        //Route::get('/action', ['middleware' => ['role:action|access'], 'as' => 'editor.action.index', 'uses' => 'ActionController@index']);
        // Route::get('/action', 'ActionController@index')->name('action.index');
        Route::post('/action', 'ActionController@store')->name('action.store');
        Route::post('/action/{id}/update', 'ActionController@update')->name('action.update');
        Route::delete('/action/{id}/delete', 'ActionController@delete')->name('action.delete');
        // Route::post('/action/bulkdelete', 'ActionController@bulkDelete')->name('action.bulkdelete');

        /////hrd
        //Announcement
        Route::get('/hrd/announcement', 'HrdAnnouncementController@index')->name('announcement.index');
        Route::get('/hrd/announcement/delete/{id?}', 'HrdAnnouncementController@delete')->name('announcement.delete');
        Route::get('/hrd/announcement/activate/{id?}', 'HrdAnnouncementController@activate')->name('announcement.activate');
        Route::get('/hrd/announcement/detail/{id?}', 'HrdAnnouncementController@detail')->name('announcement.detail');
        Route::post('hrd/announcement/add', 'HrdAnnouncementController@add')->name('announcement.add');

        //employee
        Route::get('/hrd/getEmployee_data','HrdEmployeeController@getEmpGet')->name('employee.getdata');
        Route::post('/hrd/getEmployee_data_post','HrdEmployeeController@getEmp')->name('employee.getdata_post');
        Route::get('/hrd/employee', 'HrdEmployeeController@index')->name('employee.index');
        Route::get('/hrd/employee/expel/{id}', 'HrdEmployeeController@expelEmp')->name('employee.expel');
        Route::get('/hrd/employee/nik','HrdEmployeeController@nikFunction')->name('employee.nik');
        Route::get('/hrd/employee/thp','HrdEmployeeController@thpBreakdown')->name('employee.thp');
        Route::post('/hrd/employee/store','HrdEmployeeController@store')->name('employee.add');
        Route::get('/hrd/employee/{id}/detail','HrdEmployeeController@getDetail')->name('employee.detail');
        Route::post('/hrd/employee/{id}/delete','HrdEmployeeController@delete')->name('employee.delete');
        Route::post('/hrd/employee/{id}/update','HrdEmployeeController@update')->name('employee.update');
        Route::post('/hrd/employee/{id}/updateAttach','HrdEmployeeController@updateAttach')->name('employee.updateAttach');
        Route::post('/hrd/employee/{id}/updateJoinDate','HrdEmployeeController@updateJoinDate')->name('employee.updateJoinDate');
        Route::post('/hrd/employee/{id}/updateFinMan','HrdEmployeeController@updateFinMan')->name('employee.updateFinMan');
        Route::post('/hrd/employee/{id}/updateInsurance','HrdEmployeeController@updateInsurance')->name('employee.updateInsurance');
        Route::post('/hrd/employee/detail/needsec/submit','HrdEmployeeController@submitNeedsec')->name('emp_fin.needsec.submit');
        Route::post('/hrd/employee/renewcontract','HrdEmployeeController@addContract')->name('employee.addcontract');
        Route::post('/hrd/employee/cv','HrdEmployeeController@cv')->name('employee.cv');
        Route::get('/hrd/employee/cv/delete/{id?}','HrdEmployeeController@cv_delete')->name('employee.cv_delete');
        Route::get('/hrd/employee/cv/print/{id?}','HrdEmployeeController@cv_print')->name('employee.cv_print');

        Route::get('/hrd/employee/activity', 'HrdEmployeeActivity@index')->name('employee.activity.index');
        Route::get('/hrd/employee/activity/get', 'HrdEmployeeActivity@get')->name('employee.activity.get');
        Route::get('/hrd/employee/activity/detail/{id?}', 'HrdEmployeeActivity@detail')->name('employee.activity.detail');
        Route::post('/hrd/employee/activity/detail-chart/{id?}', 'HrdEmployeeActivity@detail_chart')->name('employee.activity.detail-chart');

        Route::post('/hrd/employee/ppe/generate', "HrdEmployeeController@generate_ppe")->name("employee.hrd.generate_ppe");
        Route::post('/hrd/employee/ppe/disable', "HrdEmployeeController@disable_ppe")->name("employee.hrd.disable_ppe");

        Route::get("/hrd/employee/get_data/{id?}", "HrdEmployeeController@generate_data");

        Route::post('/hrd/employee/storeCV', 'HrdEmployeeController@storeCV')->name('employee.storeCV');
        ROute::post('/hrd/employee/storeVaccine', 'HrdEmployeeController@storeVaccine')->name('employee.storeVaccine');
        Route::get('/hrd/employee/deletecv/{id}', 'HrdEmployeeController@deleteCV')->name('employee.deleteCV');

        Route::get('/hrd/employee/company/{id?}', 'HrdEmployeeController@empCompany')->name("employee.comp");


        //loanemployee
        Route::get('/hrd/employee/loan','HrdEmployeeController@getIndexEmployeeLoan')->name('employee.loan');
        Route::post('/hrd/employee/loan/{id}/loandelete','HrdEmployeeController@loandelete')->name('employee.loan.delete');
        Route::post('/hrd/employee/loan/store','HrdEmployeeController@addLoan')->name('employee.loan.store');
        Route::get('/hrd/employee/loan/{id}/detail','HrdEmployeeController@getDetailLoan')->name('employee.loan.detail');
        Route::post('/hrd/employee/loan/payment', 'HrdEmployeeController@storeLoanPayment')->name('employee.loan.payment');

        //overtime
        Route::get('/hrd/overtime','HrdOvertimeController@index')->name('overtime.index');
        Route::post('/hrd/overtime','HrdOvertimeController@getOvertime')->name('overtime.ot');
        Route::post('/hrd/overtime/store','HrdOvertimeController@storeOvertime')->name('overtime.storeOvertime');
        Route::get('/hrd/overtime/{id}/detail/{year}/{month}','HrdOvertimeController@getDetail')->name('overtime.detail');

        //subsidies
        Route::get('/hrd/subsidies','HrdBonusController@index')->name('subsidies.index');
        Route::post('/hrd/subsidies/store', 'HrdBonusController@addSubsidies')->name('subsidies.store');
        Route::post('/hrd/subsidies/{id}/delete','HrdBonusController@delete')->name('subsidies.delete');
        Route::get('/hrd/subsidies/{id}/payment','HrdBonusController@getDetailBonus')->name('subsidies.payment');
        Route::post('/hrd/subsidies/payment/store', 'HrdBonusController@storePayment')->name('subsidies.payment.store');

        // PAYROLL
        Route::get('/hrd/payroll', 'HrdPayrollController@index')->name('payroll.index');
        Route::post('/hrd/payroll', 'HrdPayrollController@show')->name('payroll.show');
        Route::post('/hrd/payroll/export', 'HrdPayrollController@export')->name('payroll.export');
        Route::get('/hrd/payroll/remarks-btl', 'HrdPayrollController@print_btl')->name('payroll.remarks_btl');
        Route::get('/hrd/payroll/print-btl', 'HrdPayrollController@print_btl')->name('payroll.print_btl');
        Route::post('/hrd/payroll/update', 'HrdPayrollController@update')->name('payroll.update');
        Route::post('/hrd/payroll/remarks-save', 'HrdPayrollController@save_remarks')->name('payroll.remarks_save');
        Route::post('/hrd/payroll/total-payroll', 'HrdPayrollController@get_total_payroll')->name('payroll.get.total');

        //POINT
        Route::get('/hrd/point', 'HrdPointController@index')->name('point.index');
        Route::get('/hrd/point/delete/{id?}', 'HrdPointController@delete')->name('point.delete');
        Route::post('/hrd/point/add', 'HrdPointController@add')->name('point.add');
        Route::post('/hrd/point/approve', 'HrdPointController@approve')->name('point.approve');

        // SEVERANCE
        Route::get('/hrd/severance', 'HrdSeveranceController@index')->name('severance.index');
        Route::get('/hrd/severance/delete/{id?}', 'HrdSeveranceController@delete')->name('severance.delete');
        Route::post('/hrd/severance/add', 'HrdSeveranceController@add')->name('severance.add');
        Route::post('/hrd/severance/approve', 'HrdSeveranceController@approve')->name('severance.approve');
        Route::get('/hrd/severance/print/{id?}', 'HrdSeveranceController@print')->name('severance.print');

        //needsec
        Route::get('/hrd/payroll/needsec', 'HrdPayrollController@needsec')->name('payroll.needsec');
        Route::post('/hrd/payroll/needsec/submit', 'HrdPayrollController@submitNeedsec')->name('payroll.submitNeedsec');
        Route::get('/hrd/salarylist/needsec', 'SalaryListController@needsec')->name('salarylist.needsec');
        Route::post('/hrd/salarylist/needsec/submit', 'SalaryListController@submitNeedsec')->name('salarylist.submitNeedsec');
        Route::get('/hrd/payroll/slip/{id?}/{period?}', 'HrdPayrollController@print_slip')->name('payroll.slip.print');

        // //training
        // Route::get('/hrd/training','HrdTrainingController@index')->name('training.index');
        // Route::post('/hrd/training/store', 'HrdTrainingController@store')->name('training.store');
        // Route::post('/hrd/training/{id}/update','HrdTrainingController@update')->name('training.update');
        // Route::post('/hrd/training/{id}/delete','HrdTrainingController@delete')->name('training.delete');
        // Route::post('/hrd/training/{docid}/deletedoc','HrdTrainingController@deleteDoc')->name('training.deletedoc');
        // Route::post('/hrd/training/{docid}/deletevid','HrdTrainingController@deleteVid')->name('training.deletevid');

         //training
        Route::get('/hrd/training','HrdTrainingController@index')->name('training.index');
        Route::get('/hrd/training/detail/{id}','HrdTrainingController@getDetailTraining')->name('training.detail');
        Route::post('/hrd/training/store', 'HrdTrainingController@store')->name('training.store');
        Route::post('/hrd/training/{id}/update','HrdTrainingController@update')->name('training.update');
        Route::post('/hrd/training/{id}/delete','HrdTrainingController@delete')->name('training.delete');
        Route::post('/hrd/training/{docid}/deletedoc','HrdTrainingController@deleteDoc')->name('training.deletedoc');
        Route::post('/hrd/training/{docid}/deletevid','HrdTrainingController@deleteVid')->name('training.deletevid');
        Route::post('/hrd/training/saveScoreUsers','HrdTrainingController@saveScore')->name('training.saveScore');
        Route::post('/hrd/training/addParticipants','HrdTrainingController@saveParticipant')->name('training.saveParticipant');
        Route::get('/hrd/training/deleteParticipant/{id}','HrdTrainingController@deleteParticipant')->name('training.deleteparticipant');

        //training point
        Route::post('/hrd/settingpoint', 'HrdTrainingController@settingPoint')->name('settingpoint.store');

        //decree
        Route::get('/hrd/official-letter','UtilDecreeController@index')->name('decree.index');
        Route::post('/hrd/official-letter', 'UtilDecreeController@addDecree')->name('decree.store');
        Route::get('/hrd/official-letter/delete/{id}','UtilDecreeController@delete')->name('decree.delete');

        //policy
        Route::get('/hrd/policy','PolicyMainController@index')->name('policy.index');
        Route::get('/hrd/policy/category','PolicyMainController@indexCategory')->name('policy.category');
        Route::get('/hrd/policy/category/delete/{id?}','PolicyMainController@deleteCategory')->name('policy.category.delete');
        Route::post('/hrd/policy/store','PolicyMainController@store')->name('policy.store');
        Route::post('/hrd/policy/detail/store','PolicyMainController@storeDetailPolicy')->name('policy.storeDetail');
        Route::post('/hrd/policy/category/storeCategory','PolicyMainController@storeCategory')->name('policy.storeCategory');
        Route::get('/hrd/policy/delete/{id}','PolicyMainController@delete')->name('policy.delete');
        Route::get('/hrd/policy/detail/{id}','PolicyMainController@getDetailPolicy')->name('policy.detail');
        Route::get('/hrd/policy/detail/delete/{id}/{id_main}','PolicyMainController@deleteDetail')->name('policy.detail.delete');
        Route::get('/hrd/policy/detail/view/{id}/{type?}','PolicyMainController@viewApprove')->name('policy.detail.viewappr');
        Route::post('/hrd/policy/detail/viewapprove','PolicyMainController@approve')->name('policy.detail.viewappr.submit');
        Route::get('/hrd/policy/detail/print/{id}/{type?}','PolicyMainController@printView')->name('policy.detail.printView');

        //policy
        Route::get('/qhse/policy','PolicyHSEController@index')->name('policy.hse.index');
        Route::get('/qhse/policy/category','PolicyHSEController@indexCategory')->name('policy.hse.category');
        Route::get('/qhse/policy/category/delete/{id?}','PolicyHSEController@deleteCategory')->name('policy.hse.category.delete');
        Route::post('/qhse/policy/store','PolicyHSEController@store')->name('policy.hse.store');
        Route::post('/qhse/policy/detail/store','PolicyHSEController@storeDetailPolicy')->name('policy.hse.storeDetail');
        Route::post('/qhse/policy/category/storeCategory','PolicyHSEController@storeCategory')->name('policy.hse.storeCategory');
        Route::get('/qhse/policy/delete/{id}','PolicyHSEController@delete')->name('policy.hse.delete');
        Route::get('/qhse/policy/detail/{id}','PolicyHSEController@getDetailPolicy')->name('policy.hse.detail');
        Route::get('/qhse/policy/detail/delete/{id}/{id_main}','PolicyHSEController@deleteDetail')->name('policy.hse.detail.delete');
        Route::get('/qhse/policy/detail/view/{id}/{type?}','PolicyHSEController@viewApprove')->name('policy.hse.detail.viewappr');
        Route::post('/qhse/policy/detail/viewapprove','PolicyHSEController@approve')->name('policy.hse.detail.viewappr.submit');
        Route::get('/qhse/policy/detail/print/{id}/{type?}','PolicyHSEController@printView')->name('policy.hse.detail.printView');

        //qhse

        //training record
        Route::get('/qhse/training-record', 'QhseTrainingRecordController@index')->name('qhse.tr.index');
        Route::post('/qhse/training-record/type/add', 'QhseTrainingRecordController@type_add')->name('qhse.tr.type.add');
        Route::post('/qhse/training-record/add', 'QhseTrainingRecordController@add')->name('qhse.tr.add');
        Route::get('/qhse/training-record/type/delete/{id}', 'QhseTrainingRecordController@type_delete')->name('qhse.tr.type.delete');
        Route::get('/qhse/training-record/delete/{id}', 'QhseTrainingRecordController@delete')->name('qhse.tr.delete');
        //csr
        Route::get('/qhse/csr','CSRController@index')->name('csr.index');
        Route::post('qhse/csr','CSRController@storeCSR')->name('csr.store');
        Route::post('qhse/csr/publish','CSRController@publishCSR')->name('csr.publish');
        Route::get('/qhse/csr/delete/{id}','CSRController@delete')->name('csr.delete');
        Route::get('/qhse/csr/view/{id}', 'CSRController@csr_view')->name('csr.view');

        //csms
        Route::get('/qhse/csms/', 'QhseCsmsController@index')->name('qhse.csms.index');
        Route::get('/qhse/csms/view/{type}/{id?}', 'QhseCsmsController@view')->name('qhse.csms.view');
        Route::get('/qhse/csms/input/{id?}', 'QhseCsmsController@input_step')->name('qhse.csms.input_step');
        Route::get('/qhse/csms/delete/{type?}/{id?}', 'QhseCsmsController@delete')->name('qhse.csms.delete');
        Route::get('/qhse/csms/change/{type?}/{x?}/{y?}', 'QhseCsmsController@change')->name('qhse.csms.change');
        Route::post('/qhse/csms/add', 'QhseCsmsController@add')->name('qhse.csms.add');
        Route::post('/qhse/csms/add-step', 'QhseCsmsController@add_step')->name('qhse.csms.add.step');
        Route::post('/qhse/csms/add-input', 'QhseCsmsController@add_input')->name('qhse.csms.add.input');
        Route::get('/qhse/csms/print/{id?}', 'QhseCsmsController@print')->name('qhse.csms.print');

        // csms_files
        Route::post('/qhse/csms/files/upload','QhseCsmsController@files_upload')->name('qhse.csms.files.upload');
        Route::get('/qhse/csms/files/delete/{id?}','QhseCsmsController@files_delete')->name('qhse.csms.files.delete');

        // csms_meetings
        Route::post('/qhse/csms/meetings/create','QhseCsmsController@meetings_create')->name('qhse.csms.meetings.create');
        Route::post('/qhse/csms/meetings/mom','QhseCsmsController@meetings_mom')->name('qhse.csms.meetings.mom');
        Route::get('/qhse/csms/meetings/get/{id?}','QhseCsmsController@meetings_get')->name('qhse.csms.meetings.get');

        // csms_ol
        Route::post('/qhse/csms/ol/create','QhseCsmsController@ol_create')->name('qhse.csms.ol.create');
        Route::post('/qhse/csms/ol/update','QhseCsmsController@ol_update')->name('qhse.csms.ol.update');
        Route::get('/qhse/csms/ol/delete/{id?}','QhseCsmsController@ol_delete')->name('qhse.csms.ol.delete');
        Route::get('/qhse/csms/ol/get/{id?}','QhseCsmsController@ol_get')->name('qhse.csms.ol.get');

        // csms_su
        Route::post('/qhse/csms/su/create','QhseCsmsController@su_create')->name('qhse.csms.su.create');
        Route::post('/qhse/csms/su/field','QhseCsmsController@su_create_field')->name('qhse.csms.su.field');
        Route::post('/qhse/csms/su/update','QhseCsmsController@su_update')->name('qhse.csms.su.update');
        Route::get('/qhse/csms/su/delete/{id?}','QhseCsmsController@su_delete')->name('qhse.csms.su.delete');
        Route::get('/qhse/csms/su/get/{id?}','QhseCsmsController@su_get')->name('qhse.csms.su.get');
        Route::get('/qhse/csms/su/form-add/{id?}','QhseCsmsController@su_form')->name('qhse.csms.su.form');
        Route::post('/qhse/csms/su/add-row','QhseCsmsController@su_add_row')->name('qhse.csms.su.add_row');
        Route::post('/qhse/csms/su/delete-row/','QhseCsmsController@su_delete_row')->name('qhse.csms.su.delete_row');

        // csms_tt
        Route::post('/qhse/csms/tt/create','QhseCsmsController@tt_create')->name('qhse.csms.tt.create');
        Route::post('/qhse/csms/tt/update','QhseCsmsController@tt_update')->name('qhse.csms.tt.update');
        Route::get('/qhse/csms/tt/delete/{id?}','QhseCsmsController@tt_delete')->name('qhse.csms.tt.delete');
        Route::get('/qhse/csms/tt/follow/{id?}','QhseCsmsController@tt_follow')->name('qhse.csms.tt.follow');

        // csms_links
        Route::post('/qhse/csms/links/create', 'QhseCsmsController@links_create')->name('qhse.csms.links.create');
        Route::post('/qhse/csms/links/update','QhseCsmsController@links_update')->name('qhse.csms.links.update');
        Route::get('/qhse/csms/links/delete/{id?}','QhseCsmsController@links_delete')->name('qhse.csms.links.delete');
        Route::get('/qhse/csms/links/follow/{id?}','QhseCsmsController@links_follow')->name('qhse.csms.links.follow');

        //mcu
        Route::get('/qhse/mcu','MCUController@index')->name('mcu.index');
        Route::post('/qhse/mcu','MCUController@storeMCU')->name('mcu.store');
        Route::post('/qhse/mcu/log','MCUController@storeMCULog')->name('mcu.storeLog');
        Route::get('/qhse/mcu/delete/{id}','MCUController@delete')->name('mcu.delete');
        Route::get('/qhse/mcu/log/{id}','MCUController@getLogMCU')->name('mcu.logs');

        //nearmiss
        Route::get('/qhse/near-miss','NearMissController@index')->name('nearmiss.index');
        Route::get('/qhse/near-miss/view/{id?}/{status?}','NearMissController@getview')->name('nearmiss.getview');
        Route::get('/qhse/near-miss/photo/{id?}/{status?}','NearMissController@getviewphoto')->name('nearmiss.getviewphoto');
        Route::get('/qhse/near-miss/view_page/{id}','NearMissController@nm_view')->name('nearmiss.nm_view');
        Route::post('/qhse/near-miss/store','NearMissController@store')->name('nearmiss.store');
        Route::post('/qhse/near-miss/updatePhoto','NearMissController@updatePhoto')->name('nearmiss.updatePhoto');
        Route::post('/qhse/near-miss/delete','NearMissController@delete')->name('nearmiss.delete');
        Route::post('/qhse/near-miss/approval','NearMissController@approval')->name('nearmiss.approval');

        //safetymeeting
        Route::get('/general/safety-meeting','SafetyMeetingController@index')->name('sm.index');
        Route::get('/general/safety-meetingAjax','SafetyMeetingController@getMtgAjax')->name('sm.get');
        Route::get('/general/safety-meetingAttendance/{id?}','SafetyMeetingController@getAbsence')->name('sm.getAbsence');
        Route::get('/general/safety-meetingMom/{id?}','SafetyMeetingController@getMom')->name('sm.getMom');
        Route::post('/general/safety-meeting','SafetyMeetingController@storeMain')->name('sm.store');
        Route::post('/general/safety-meeting/signatureSave','SafetyMeetingController@signatureSave')->name('sm.sign.save');
        Route::post('/general/safety-meeting/signatureFileSave','SafetyMeetingController@signatureFileSave')->name('sm.file.save');
        Route::get('/general/safety-meeting/detail/{id?}','SafetyMeetingController@getDetail')->name('sm.detail');
        Route::get('/general/safety-meeting/actionprogress/{id?}','SafetyMeetingController@setActionProgress')->name('sm.action.progress');
        Route::get('/general/safety-meeting/delete/{id?}','SafetyMeetingController@deleteMain')->name('sm.delete.main');
        Route::get('/general/safety-meeting/delete/attd/{id}/{id_main}','SafetyMeetingController@deletAttd')->name('sm.delete.attd');
        Route::get('/general/safety-meeting/delete/delMOM/{id}/{id_main}','SafetyMeetingController@deletDelMOM')->name('sm.delete.delMOM');
        Route::post('/general/safety-meeting/detail/storeMOM','SafetyMeetingController@storeMOM')->name('sm.detail.storeMOM');
        Route::post('/general/safety-meeting/detail/updateMOM','SafetyMeetingController@updateMOM')->name('sm.detail.updateMOM');


        //managementvisit
        Route::get('/general/management-visit','ManagementVisitController@index')->name('mv.index');
        Route::get('/general/management-visitAjax','ManagementVisitController@getMtgAjax')->name('mv.get');
        Route::get('/general/management-visitAttendance/{id?}','ManagementVisitController@getAbsence')->name('mv.getAbsence');
        Route::get('/general/management-visitMom/{id?}','ManagementVisitController@getMom')->name('mv.getMom');
        Route::post('/general/management-visit','ManagementVisitController@storeMain')->name('mv.store');
        Route::post('/general/management-visit/signatureSave','ManagementVisitController@signatureSave')->name('mv.sign.save');
        Route::post('/general/management-visit/signatureFileSave','ManagementVisitController@signatureFileSave')->name('mv.file.save');
        Route::get('/general/management-visit/detail/{id?}','ManagementVisitController@getDetail')->name('mv.detail');
        Route::get('/general/management-visit/actionprogress/{id?}','ManagementVisitController@setActionProgress')->name('mv.action.progress');
        Route::get('/general/management-visit/delete/{id?}','ManagementVisitController@deleteMain')->name('mv.delete.main');
        Route::get('/general/management-visit/delete/attd/{id}/{id_main}','ManagementVisitController@deletAttd')->name('mv.delete.attd');
        Route::get('/general/management-visit/delete/delMOM/{id}/{id_main}','ManagementVisitController@deletDelMOM')->name('mv.delete.delMOM');
        Route::post('/general/management-visit/detail/storeMOM','ManagementVisitController@storeMOM')->name('mv.detail.storeMOM');
        Route::post('/general/management-visit/detail/updateMOM','ManagementVisitController@updateMOM')->name('mv.detail.updateMOM');
        Route::post('/general/management-visit/detail/uploadAttach','ManagementVisitController@uploadAttach')->name('mv.detail.uploadAttach');
        Route::get('/general/management-visit/detail/delete-attach/{id?}','ManagementVisitController@deleteAttach')->name('mv.detail.deleteAttach');
        Route::get('/general/management-visit/print/{id?}','ManagementVisitController@printMv')->name('mv.printMv');

        //SOP
        Route::get('/qhse/sop','SOPController@index')->name('sop.index');
        Route::get('/qhse/sop/detail/{id_main}','SOPController@getDetailSOP')->name('sop.detail');
        Route::get('/qhse/sop/detail_sop_view/{id_detail}/{act?}','SOPController@getSOPDetailView')->name('sop.detail_view');
        Route::get('/qhse/sop/add_detail/{id_main}/{status}/{id_detail?}','SOPController@getAddDetail')->name('sop.add_detail');
        Route::post('/qhse/sop','SOPController@storeMain')->name('sop.store');
        Route::post('/qhse/sop/detail','SOPController@saveDetail')->name('sop.storedetail');
        Route::get('/qhse/sop/getData' ,'SOPController@getSOPMainAjax')->name('sop.get');
        Route::get('/qhse/sop/deleteData/{id}' ,'SOPController@deleteMain')->name('sop.deletemain');
        Route::get('/qhse/sop/approval_detail/{id_detail}/{id_main}/{act}' ,'SOPController@approval')->name('sop.approval_detail');
        Route::get('/qhse/sop-category','SOPController@sop_category')->name('sop.category');
        Route::get('/qhse/sop-category/delete/{id}','SOPController@deleteCategory')->name('sop.category_del');
        Route::post('/qhse/sop-category','SOPController@saveCategory')->name('sop.saveCategory');


        //miss
        Route::get('/qhse/miss','MissController@index')->name('miss.index');
        Route::get('/qhse/miss/view/{id?}/{status?}','MissController@getview')->name('miss.getview');
        Route::get('/qhse/miss/photo/{id?}/{status?}','MissController@getviewphoto')->name('miss.getviewphoto');
        Route::get('/qhse/miss/view_page/{id}','MissController@nm_view')->name('miss.nm_view');
        Route::post('/qhse/miss/store','MissController@store')->name('miss.store');
        Route::post('/qhse/miss/updatePhoto','MissController@updatePhoto')->name('miss.updatePhoto');
        Route::post('/qhse/miss/delete','MissController@delete')->name('miss.delete');
        Route::post('/qhse/miss/approval','MissController@approval')->name('miss.approval');

//salarylist
        Route::get('/dirut/salarylist','SalaryListController@index')->name('salarylist.index');
        Route::get('/dirut/salarylist/history/{id}','SalaryListController@getSalaryHistory')->name('salarylist.history');
        Route::post('/dirut/salarylist/save','SalaryListController@save')->name('salarylist.save');
        Route::post('/dirut/salarylist/reset','SalaryListController@reset')->name('salarylist.reset');
        Route::post('/dirut/salarylist/generateTHR','SalaryListController@generateTHR')->name('salarylist.generateTHR');

        ////marketing
        /// client
        Route::get('/marketing/clients','MarketingClients@index')->name('marketing.client.index');
        Route::post('/marketing/store','MarketingClients@store')->name('marketing.client.store');
        Route::get('/marketing/{id}/delete','MarketingClients@delete')->name('marketing.client.delete');
        Route::post('/marketing/update','MarketingClients@update')->name('marketing.client.update');
        Route::post('/marketing/add-js','MarketingClients@add_js')->name('marketing.client.add.js');
        Route::get('/marketing/get-clients','MarketingClients@get_clients')->name('marketing.client.get.js');

        /// leads
        Route::get('/marketing/leads', 'MarketingLeadsController@index')->name('leads.index');
        Route::get('/marketing/leads/delete/{id?}', 'MarketingLeadsController@delete')->name('leads.delete');
        Route::get('/marketing/leads/view/{id?}', 'MarketingLeadsController@view')->name('leads.view');
        Route::post('/marketing/leads/add', 'MarketingLeadsController@add')->name('leads.add');
        Route::post('/marketing/leads/edit', 'MarketingLeadsController@edit')->name('leads.edit');
        Route::post('/marketing/leads/update_progress', 'MarketingLeadsController@update_progress')->name('leads.update_progress');
        Route::post('/marketing/leads/upload-file', 'MarketingLeadsController@upload_file')->name('leads.upload_file');
        Route::get('/marketing/leads/delete-file/{id?}', 'MarketingLeadsController@delete_file')->name('leads.delete_file');
        Route::post('/marketing/leads/add-contributors', 'MarketingLeadsController@add_contributors')->name('leads.add_contributors');
        Route::post('/marketing/leads/edit/partner', 'MarketingLeadsController@edit_partner')->name('leads.edit_partner');
        Route::get('/marketing/leads/approve/{id?}','MarketingLeadsController@approveLeads')->name('leads.approve');
        Route::post('/marketing/leads/upload-progress/{type?}', 'MarketingLeadsController@upload_progress')->name('leads.upload_progress');
        Route::get('/marketing/leads/management', 'MarketingLeadsController@index_management')->name('leads.index_management');
        Route::post('/marketing/leads/category/add','MarketingLeadsController@insertLeadsCategory')->name('leads.cat.add');
        Route::get('/marketing/get-leadscat','MarketingLeadsController@get_categories')->name('leads.get_categories.js');

        //contract leads
        Route::post('/marketing/leads/addContract','MarketingLeadsController@addContracts')->name('lead.contract.add');
        Route::post('/marketing/leads/editContract','MarketingLeadsController@editContracts')->name('lead.contract.edit');
        Route::post('/marketing/leads/editInvContract','MarketingLeadsController@editInvContracts')->name('lead.contract.editInv');
        Route::get('/marketing/leads/{id_lead}/contract/{id}/delete','MarketingLeadsController@deleteContracts')->name('lead.contract.delete');

        //note leads
        Route::post('/marketing/leads/addNote','MarketingLeadsController@addNotes')->name('notes.store');
        Route::get('/marketing/leads/{id_lead}/note/{id}/delete','MarketingLeadsController@deleteNotes')->name('notes.delete');

        //task lead
        Route::post('/marketing/lead/addTasks','MarketingLeadsController@addTasks')->name('tasks.store');
        Route::post('/marketing/lead/followup', 'MarketingLeadsController@taskFollow')->name('tasks.follow');
        Route::get('/marketing/leads/{id}/task/{id_task}/delete','MarketingLeadsController@deleteTasks')->name('task.delete');

        //meeting leads
        Route::post('/marketing/lead/addMeeting','MarketingLeadsController@addMeetings')->name('meetings.store');
        Route::get('/marketing/leads/{id}/meeting/{id_meeting}/delete','MarketingLeadsController@deleteMeetings')->name('meeting.delete');

        /// projects
        Route::get('/marketing/projects/{view?}','MarketingProjectsController@indexProjects')->name('marketing.project');
        Route::get('/marketing/projects/detail/{id?}','MarketingProjectsController@view')->name('marketing.project.view');
        Route::get('/marketing/projects/equipments/{id?}','MarketingProjectsController@equipments')->name('marketing.project.equipments');
        Route::post('/marketing/projects/equipments/{id?}','MarketingProjectsController@save_equipments')->name('marketing.project.save_equipments');
        Route::get('/marketing/projects/get/equipments/{id?}','MarketingProjectsController@get_equipments')->name('marketing.project.get_equipments');
        Route::post('/marketing/projects/store','MarketingProjectsController@store')->name('marketing.project.store');
        Route::post('/marketing/projects/update','MarketingProjectsController@update')->name('marketing.project.update');
        Route::get('/marketing/projects/attachment/{id?}', 'MarketingProjectsController@attachment')->name('marketing.project.attachment');
        Route::post('/marketing/projects/add-attachment', 'MarketingProjectsController@add_attachment')->name('marketing.project.attachment.add');
        Route::get('/marketing/projects/delete-attachment/{id?}', 'MarketingProjectsController@delete_attachment')->name('marketing.project.attachment.delete');
        Route::get('/marketing/projects/status/{type?}/{id?}','MarketingProjectsController@change_status')->name('marketing.project.change_status');

        // prognosis
        Route::get('/marketing/prognosis/create/{id}','MarketingPrognosisController@index')->name('marketing.prognosis.index');
        Route::get('/marketing/prognosis/calc/{id}','MarketingPrognosisController@view')->name('marketing.prognosis.view');
        Route::get('/marketing/prognosis/modal/{type?}/{id?}','MarketingPrognosisController@modal')->name('marketing.prognosis.modal');
        Route::post('/marketing/prognosis/add','MarketingPrognosisController@add')->name('marketing.prognosis.add');
        Route::get('/marketing/prognosis/delete/{id?}','MarketingPrognosisController@delete')->name('marketing.prognosis.delete');
        Route::post('/marketing/prognosis/update/','MarketingPrognosisController@update')->name('marketing.prognosis.update');
        Route::post('/marketing/prognosis/whitelists/{type}','MarketingPrognosisController@whitelists')->name('marketing.prognosis.whitelists');
        Route::post('/marketing/prognosis/project/{code}','MarketingPrognosisController@project')->name('marketing.prognosis.project');
        Route::get('/marketing/prognosis/excel/{id}','MarketingPrognosisController@excel_export')->name('marketing.prognosis.excel_export');


        // custom chart
        Route::get('/chart/custom-chart', 'ChartCustomController@index')->name('chart.custom.index');
        Route::get('/chart/custom-chart/view/{id?}', 'ChartCustomController@view')->name('chart.custom.view');
        Route::get('/chart/custom-chart/find/{id?}', 'ChartCustomController@find')->name('chart.custom.find');
        Route::get('/chart/custom-chart/delete/{id?}', 'ChartCustomController@delete')->name('chart.custom.delete');
        Route::post('/chart/custom-chart/add', 'ChartCustomController@add')->name('chart.custom.add');
        Route::post('/chart/custom-chart/update', 'ChartCustomController@update')->name('chart.custom.update');
        Route::get('/chart/custom-chart/get/{id_chart?}', 'ChartCustomController@get_data')->name('chart.custom.get_data');

        ////General
        ///Daily Report
        Route::get('/general/daily-report','GeneralDailyReport@index')->name('general.dr');
        Route::get('/general/daily-report/delete/{id}','GeneralDailyReport@delete')->name('general.dr.delete');
        Route::get('/general/daily-report/view/{id?}/{appr?}','GeneralDailyReport@viewPage')->name('general.dr.view');
        Route::get('/general/daily-report/inventory/{id}/{division}','GeneralDailyReport@getDataInventory')->name('general.dr.inventory');
        Route::post('/general/daily-report/post','GeneralDailyReport@store')->name('general.dr.store');
        Route::post('/general/daily-report/itemInit','GeneralDailyReport@insertInitInventory')->name('general.dr.init_item');
        Route::post('/general/daily-report/inQty','GeneralDailyReport@postInQty')->name('general.dr.in_qty');
        Route::post('/general/daily-report/outQty','GeneralDailyReport@postOutQty')->name('general.dr.out_qty');
        Route::post('/general/daily-report/lock','GeneralDailyReport@lockInventory')->name('general.dr.lock');

        // SO - SE
        Route::get('/general/list/sre/{type?}/{category?}', 'AssetSreController@lists')->name('sre.list');
        Route::get('/general/list/{type?}/delete/{id?}', 'AssetSreController@delete')->name('sre.delete');

        /// SO
         Route::get('/general/sowaiting','AssetSreController@getSoWaiting')->name('so.waiting');
        Route::get('/general/sobank','AssetSreController@getSoBank')->name('so.bank');
        Route::get('/general/soreject','AssetSreController@getSoReject')->name('so.rejected');
        Route::get('/general/so', 'AssetSreController@so_index')->name('general.so');
        Route::post('/general/so/add', 'AssetSreController@so_add')->name('so.add');
        Route::get('/general/so/appr/{id}', 'AssetSreController@so_appr')->name('so.appr');
        Route::get('/general/so/view/{id}', 'AssetSreController@so_view')->name('so.view');
        Route::post('/general/so/approve', 'AssetSreController@so_approve')->name('so.approve');
        Route::post('/general/so/reject', 'AssetSreController@so_reject')->name('so.reject');
        Route::get('/general/delete/{type}/{id}', 'AssetSreController@delete')->name('so.delete');

        /// SR
        Route::get('/general/sr', 'AssetSreController@sr_index')->name('sr.index');
        Route::get('/general/sr/view/{id}', 'AssetSreController@sr_view')->name('sr.view');
        Route::get('/general/sr/appr/{id}', 'AssetSreController@sr_appr')->name('sr.appr');
        Route::post('/general/sr/approve', 'AssetSreController@sr_approve')->name('sr.approve');
        Route::post('/general/sr/reject', 'AssetSreController@sr_reject')->name('sr.reject');

        /// SE
        Route::get('/general/se', 'AssetSreController@se_index')->name('se.index');
        Route::get('/general/se/appr/{id}', 'AssetSreController@se_appr')->name('se.appr');
        Route::get('/general/se/view/{id}', 'AssetSreController@se_view')->name('se.view');
        Route::post('/general/se/approve', 'AssetSreController@se_approve')->name('se.approve');
        Route::post('/general/se/reject', 'AssetSreController@se_reject')->name('se.reject');
        Route::post('/general/se/input', 'AssetSreController@se_approve')->name('se.input_post');
        Route::post('/general/se/ack', 'AssetSreController@se_approve')->name('se.ack_post');
        Route::post('/general/se/dir', 'AssetSreController@se_approve')->name('se.dir_post');
        Route::post('/general/se/reject', 'AssetSreController@se_reject')->name('se.reject');

        /// WO
        Route::get('/general/wo', 'AssetWoController@index')->name('general.wo');
        Route::get('/general/wo/appr/{id}', 'AssetWoController@appr')->name('wo.appr');
        Route::get('/general/wo/view/{id}', 'AssetWoController@detail')->name('wo.view');
        Route::post('/general/wo/approve', 'AssetWoController@approve')->name('wo.approve');
        Route::post('/general/wo/reject', 'AssetWoController@reject')->name('wo.reject');
        Route::post('/general/wo/revise', 'AssetWoController@revise')->name('wo.revise');
        Route::get('/general/wo/delete/{id?}', 'AssetWoController@delete')->name('wo.delete');
        Route::post('/general/wo/add-instant', 'AssetWoController@addInstant')->name('wo.addInstant');
        Route::post('/general/wo/edit/notes', 'AssetWoController@edit_notes')->name('wo.edit.notes');

        // Leave
        Route::get('/leave', 'LeaveController@index')->name('leave.index');
        Route::get('/leave/request', 'LeaveController@request_form')->name('leave.request');
        Route::post('/leave/submit', 'LeaveController@submit')->name('leave.submit');
        Route::post('/leave/checkcuti', 'LeaveController@checkcuti')->name('leave.checkcuti');
        Route::post('/leave/approve', 'LeaveController@approve')->name('leave.approve');
        Route::get('/leave/delete/{id?}', 'LeaveController@delete')->name('leave.delete');

        //TO
        Route::get('/general/to/{id}/delete','GeneralTravelOrderController@delete')->name('to.delete');
        Route::get('/general/to','GeneralTravelOrderController@index')->name('to.index');
        Route::post('/general/to/add', 'GeneralTravelOrderController@addFirst')->name('to.add');
        Route::post('/general/to/store','GeneralTravelOrderController@store')->name('to.store');
        Route::get('/general/to/{id}/edit','GeneralTravelOrderController@edit')->name('to.edit');
        Route::post('/general/to/update','GeneralTravelOrderController@update')->name('to.update');
        Route::get('/general/to/{id}/ftdetail','GeneralTravelOrderController@getFTdetail')->name('to.ftdetail');
        Route::get('/general/to/{id}/timesheet_approval/{code}','GeneralTravelOrderController@getTimeSheetAppr')->name('to.tsappr');
        Route::post('/general/to/ts_approve','GeneralTravelOrderController@doTSAppr')->name('to.tsdoappr');
        Route::post('/general/to/ts_check','GeneralTravelOrderController@doCheckAppr')->name('to.doCheckAppr');
        Route::post('/general/to/ts_recheck','GeneralTravelOrderController@doCheckAppr')->name('to.doReCheckAppr');
        Route::post('/general/to/ts_pay','GeneralTravelOrderController@doPayAppr')->name('to.doPayAppr');
        Route::get('/general/to/print/{type}/{id?}','GeneralTravelOrderController@print_to')->name('to.print.to');
        Route::get('/general/to/ticketing', 'GeneralTravelOrderController@ticketing')->name('to.ticketing');
        Route::post('/general/to/ticket/se/{type}', 'GeneralTravelOrderController@ticket_se')->name('to.ticket.se');

        //COA
        $tc = "tc";
        // $pref = DB::select('select * from preference_config where id_company = ?', [1]);
        // // $pref = Preference_config::where('id_company', 1)->first();
        // if(!empty($pref) && !empty($pref->transaction_initial)){
        //     $tc = strtolower($pref->transaction_initial);
        // }
        Route::get('/finance/'.$tc,'FinanceCOAController@index')->name('coa.index');
        Route::post('/finance/'.$tc.'/store','FinanceCOAController@store')->name('coa.store');
        Route::get('/finance/'.$tc.'/delete/{id?}','FinanceCOAController@delete')->name('coa.delete');
        Route::get('/finance/'.$tc.'/get', 'FinanceCOAController@getCoa')->name('coa.get');
        Route::get('/finance/'.$tc.'/list/{id?}', 'FinanceCOAController@list')->name('coa.list');
        Route::get('/finance/'.$tc.'/list-child/', 'FinanceCOAController@list_child')->name('coa.list_child');
        Route::get('/finance/'.$tc.'/view/{id}', 'FinanceCOAController@view')->name('coa.view');
        Route::get('/finance/'.$tc.'/edit/{id?}', 'FinanceCOAController@edit_view')->name('coa.edit');
        Route::post('/finance/'.$tc.'/find', 'FinanceCOAController@find')->name('coa.find');
        Route::get('/finance/'.$tc.'/update/{id?}', 'FinanceCOAController@update')->name('coa.update');

        Route::get("/finance/$tc/assign/{type}/{id}", "FinanceCOAController@assign")->name('coa.assign');
        Route::post("/finance/$tc/assign/{type}/{id}", "FinanceCOAController@assign_post")->name('coa.assign.post');

        //Source COA
        Route::get("/finance/$tc/source", 'FinanceCOAController@source')->name('coa.source.index');
        Route::get("/finance/$tc/source/assignment/{type}/{id}", 'FinanceCOAController@assignment')->name('coa.source.assignment');
        Route::post("/finance/$tc/source/getdata", 'FinanceCOAController@source_data')->name('coa.source.data');
        Route::get("/finance/$tc/source/select-item/{id?}", 'FinanceCOAController@source_items')->name('coa.source.item');
        Route::post("/finance/$tc/source/signdata", 'FinanceCOAController@source_sign')->name('coa.source.sign');

        // VENDOR
        //VENDOR
        Route::get('/procurement/vendor','ProcurementVendorController@index')->name('vendor.index');
        Route::get('/procurement/vendor/{id}/edit','ProcurementVendorController@edit')->name('vendor.edit');
        Route::post('/procurement/vendor/store','ProcurementVendorController@storeVendor')->name('vendor.store');
        Route::post('/procurement/vendor/update','ProcurementVendorController@updateVendor')->name('vendor.update');
        Route::get('/procurement/vendor/{id}/delete','ProcurementVendorController@delete')->name('vendor.delete');

        // FR - PR
        Route::get('/general/list/{type?}/{categpry?}', 'AssetPreController@lists')->name('pre.list');

        // FR
         Route::get('/general/irwatings', 'AssetPreController@getFrWaiting')->name('fr.getFrWaiting');
        Route::get('/general/irbanks', 'AssetPreController@getFrBank')->name('fr.getFrBank');
        Route::get('/general/irrejects', 'AssetPreController@getFrReject')->name('fr.getFrReject');
        Route::get('/general/ir', 'AssetPreController@indexFr')->name('fr.index');
        Route::post('/general/ir/store','AssetPreController@addFr')->name('fr.add');
        Route::get('/general/ir/getProject/{cat}','AssetPreController@getProject')->name('fr.getProject');
        Route::get('/general/ir/getItems','AssetPreController@getItems')->name('fr.getItems');
        Route::get('/general/ir/view/{id}/{code?}','AssetPreController@frView')->name('fr.view');
        Route::post('/general/ir/appr/division','AssetPreController@apprDiv')->name('fr.appr.div');
        Route::post('/general/ir/appr/asset','AssetPreController@apprAsset')->name('fr.appr.asset');
        Route::post('/general/ir/appr/deliver','AssetPreController@apprDeliver')->name('fr.appr.deliver');
        Route::get('/general/ir/items/details/{id?}','AssetPreController@see_detail')->name('fr.see.detail');
        Route::get('/general/ir/items/do/{id?}','AssetPreController@to_do')->name('fr.create.do');
        Route::get('/general/{code}/delte/{id}/', 'AssetPreController@delete')->name('fr.pr.delete');
        // PR
        Route::get('/general/pr', 'AssetPreController@indexPr')->name('pr.index');
        Route::get('/general/pr/view/{id}/{code?}','AssetPreController@prView')->name('pr.view');
        Route::post('/general/pr/appr/director','AssetPreController@apprDir')->name('fr.appr.dir');

        // PE
        Route::get('/general/pe', 'AssetPreController@indexPev')->name('pe.index');
        Route::get('/general/pe/view/{id}', 'AssetPreController@pev_view')->name('pe.view');
        Route::get('/general/pe/input/{id}', 'AssetPreController@pc_apprPev')->name('pe.input');
        Route::get('/general/pe/pc/{id}', 'AssetPreController@pc_apprPev')->name('pe.pc_appr');
        Route::get('/general/pe/div/{id}', 'AssetPreController@pc_apprPev')->name('pe.div_appr');
        Route::get('/general/pe/dir/{id}', 'AssetPreController@pc_apprPev')->name('pe.dir_appr');
        Route::post('/general/pe/input', 'AssetPreController@pc_postPev')->name('pe.input_post');
        Route::post('/general/pe/pc', 'AssetPreController@pc_postPev')->name('pe.pc_post');
        Route::post('/general/pe/div', 'AssetPreController@pc_postPev')->name('pe.div_post');
        Route::post('/general/pe/dir', 'AssetPreController@pc_postPev')->name('pe.dir_post');
        Route::post('/general/pe/dir/reject', 'AssetPreController@rejectPev')->name('pe.reject');

        //meeting scheduler

        Route::get('/forum/meeting-scheduler','GeneralMeetingScheduler@index')->name('ms.index');
        Route::get('/forum/meeting-scheduler/{tanggal}','GeneralMeetingScheduler@getRoom')->name('ms.day');
        Route::post('/forum/meeting-scheduler/storeRoom','GeneralMeetingScheduler@newRoom')->name('ms.newroom');
        Route::get('/forum/meeting-scheduler/{tanggal}/book/{id_room}','GeneralMeetingScheduler@getNewBook')->name('ms.book');
        Route::post('/forum/meeting-scheduler/storeRv','GeneralMeetingScheduler@addReservation')->name('ms.addReservation');
        Route::get('/forum/meeting-scheduler/{tanggal}/room/{id_room}/event/{id_book}','GeneralMeetingScheduler@getEvent')->name('ms.event');
        Route::post('/forum/meeting-scheduler/storeEv','GeneralMeetingScheduler@storeEvent')->name('ms.addEvent');
        Route::post('/forum/meeting-scheduler/atendees/update','GeneralMeetingScheduler@updateStatus')->name('ms.update.status');
        Route::get('/forum/meeting-scheduler/{tanggal}/absensi/{id_topic}','GeneralMeetingScheduler@getAbsensi')->name('ms.absen');

        //balance sheet
        Route::get('/finance/balance-sheet','FinanceBalanceSheetController@index')->name('bs.index');
        Route::get('/finance/balance-sheet-list', 'FinanceBalanceSheetController@list')->name('bs.list');
        Route::get('/finance/balance-sheet/child/{id?}', 'FinanceBalanceSheetController@child_edit')->name('bs.child_edit');
        Route::get('/finance/balance-sheet/child-delete/{id?}', 'FinanceBalanceSheetController@child_delete')->name('bs.child_delete');
        Route::get('/finance/balance-sheet/delete/{id?}', 'FinanceBalanceSheetController@delete_list')->name('bs.list.delete');
        Route::post('/finance/balance-sheet', 'FinanceBalanceSheetController@index')->name('bs.find');
        Route::post('/finance/balance-sheet/setting', 'FinanceBalanceSheetController@setting')->name('bs.setting');
        Route::post('/finance/balance-sheet/add', 'FinanceBalanceSheetController@add_detail')->name('bs.detail.add');
        Route::post('/finance/balance-sheet/search-value', 'FinanceBalanceSheetController@search_value')->name('bs.detail.search_value');
        Route::get('/finance/balance-sheet/export/{id?}', "FinanceBalanceSheetController@export")->name('bs.export');
        Route::get('/finance/balance-sheet/view/{id}', 'FinanceBalanceSheetController@view')->name('bs.view');

        Route::get('/finance/cashflow', 'FinanceCashflow@index')->name('finance.cf.index');
        Route::post('/finance/cashflow', 'FinanceCashflow@index')->name('finance.cf.data');
        Route::get('/finance/cashflow-list', 'FinanceCashflow@list')->name('finance.cf.list');
        Route::get('/finance/cashflow/setting', 'FinanceCashflow@settings')->name('finance.cf.settings');
        Route::post('/finance/cashflow/setting', 'FinanceCashflow@settings')->name('finance.cf.settings');
        Route::post('/finance/cashflow/pdf', 'FinanceCashflow@pdf')->name('finance.cf.pdf');
        Route::get('/finance/cashflow/find-source', 'FinanceCashflow@find_source')->name('finance.cf.find_source');
        Route::get('/finance/cashflow/delete/{id?}', 'FinanceCashflow@delete')->name('finance.cf.delete');
        Route::get('/finance/cashflow/view/{id?}', 'FinanceCashflow@view')->name('finance.cf.view');
        Route::get('/finance/cashflow/edit/{id?}', 'FinanceCashflow@edit')->name('finance.cf.edit');
        Route::get("/finance/cashflow/detail", "FinanceCashflow@detail")->name("finance.cf.detail");
        Route::post('/finance/cashflow/lock', "FinanceCashflow@lock_cf")->name('finance.cf.lock');

        //GL
        Route::get('/accounting/general-ledger', 'AccountingGeneralLedgerController@index')->name('gl.index');
        Route::post('/accounting/general-ledger/edit', 'AccountingGeneralLedgerController@edit')->name('gl.edit');
        Route::post('/accounting/general-ledger', 'AccountingGeneralLedgerController@index')->name('gl.index');

        // PO
        Route::get('/general/po', 'AssetPoController@index')->name('po.index');
        Route::get('/general/po/appr/{id}', 'AssetPoController@appr')->name('po.appr');
        Route::get('/general/po/delete/{id?}', 'AssetPoController@delete')->name('po.delete');
        Route::get('/general/po/view/{id}', 'AssetPoController@detail')->name('po.view');
        Route::post('/general/po/approve', 'AssetPoController@approve')->name('po.approve');
        Route::post('/general/po/reject', 'AssetPoController@reject')->name('po.reject');
        Route::post('/general/po/revise', 'AssetPoController@revise')->name('po.revise');
        Route::post('/general/po/add-instant', 'AssetPoController@addInstant')->name('po.addInstant');
        Route::get('/general/po/print/{id?}', 'AssetPoController@print')->name('po.print');
        Route::get('/general/po/lists/{category?}', 'AssetPoController@lists')->name('po.list');
        Route::post('/general/po/edit/notes', 'AssetPoController@edit_notes')->name('po.edit.notes');
        Route::post('/general/po/items/update', 'AssetPoController@item_update')->name('po.item.update');

        // GR
        Route::get('/general/gr', 'AssetGoodReceiveController@index')->name('gr.index');
        Route::get('/general/gr/view/{id}/{type?}','AssetGoodReceiveController@getDetail')->name('gr.detail');
        Route::post('/general/gr/approveGR','AssetGoodReceiveController@approveGR')->name('gr.appr');
        Route::get('/general/gr/detail/{id}/{type?}', 'AssetGoodReceiveController@detail')->name('gr.detail.id');
        // Treasury
        Route::get('/finance/treasury', 'FinanceTreasuryController@index')->name('treasury.index');
        Route::post('/finance/treasury', 'FinanceTreasuryController@add')->name('treasury.add');
        Route::post('/finance/treasury/delete', 'FinanceTreasuryController@del')->name('treasury.delete');
        Route::post('/finance/treasury/edit', 'FinanceTreasuryController@edit')->name('treasury.edit');
        Route::post('/finance/treasury/deposit', 'FinanceTreasuryController@deposit')->name('treasury.deposit');
        Route::get('/finance/treasury/view/{id}', 'FinanceTreasuryController@view_treasure')->name('treasury.view');
        Route::post('/finance/treasury/approve', 'FinanceTreasuryController@approve')->name('treasury.approve');
        Route::post('/finance/treasury/reject', 'FinanceTreasuryController@reject')->name('treasury.reject');
        Route::post('/finance/treasury/find', 'FinanceTreasuryController@find')->name('treasury.find');
        Route::get('/ledger/{type?}/history/{id}', 'FinanceTreasuryController@history')->name('treasury.history');
        Route::get('/finance/treasury/'.$tc.'/{id}', 'FinanceTreasuryController@coa')->name('treasury.coa');
        Route::post('/finance/treasury/'.$tc.'/', 'FinanceTreasuryController@setcoa')->name('treasury.setcoa');
        Route::post('/finance/treasury/'.$tc.'/edit', 'FinanceTreasuryController@editcoa')->name('treasury.editcoa');
        Route::get('/finance/treasury/'.$tc.'/set/{id}', 'FinanceTreasuryController@viewcoa')->name('treasury.viewcoa');
        Route::post('/finance/treasury/sp/find', 'FinanceTreasuryController@findsp')->name('treasury.findsp');
        Route::post('/finance/treasury/sp/add', 'FinanceTreasuryController@addsp')->name('treasury.addsp');
        Route::get('/finance/treasury/sp/view/{id?}', 'FinanceTreasuryController@viewsp')->name('treasury.viewsp');
        Route::post('/finance/treasury/sp/appr', 'FinanceTreasuryController@apprsp')->name('treasury.apprsp');
        Route::get('/finance/treasury/sp/print/{id?}', 'FinanceTreasuryController@printsp')->name('treasury.printsp');
        Route::post('/finance/treasury/history/js', 'FinanceTreasuryController@historyjs')->name('treasury.historyjs');
        Route::post('/finance/treasury/history/change-date', 'FinanceTreasuryController@change_date')->name('treasury.change.date');
        Route::post('/finance/treasury/hold-amount', 'FinanceTreasuryController@hold_amount')->name('treasury.hold.amount');
        Route::post('/finance/treasury/transfer', 'FinanceTreasuryController@transfer')->name('treasury.transfer');

        Route::get('/finance/treasury/helo/{bulan}/{tahun}', 'FinanceTreasuryController@hello');
        Route::get('finance/treasury/hiscoa/', 'FinanceTreasuryController@hiscoa')->name('hiscoa.index');
        Route::post('finance/treasury/hiscoa/', 'FinanceTreasuryController@hiscoa');
        Route::post('finance/treasury/hiscoa/result', 'FinanceTreasuryController@hiscoa')->name('hiscoa.result');

        // SP Treasury
        Route::get('finance/treasure-sp/{id?}', 'FinanceTreasureSpController@index')->name('treasure.sp.index');
        Route::get('finance/treasure-sp/list/{id?}', 'FinanceTreasureSpController@list')->name('treasure.sp.list');
        Route::get('finance/treasure-sp/input/{id?}', 'FinanceTreasureSpController@sp_input')->name('treasure.sp.sp_input');
        Route::get('finance/treasure-sp/view/{id?}', 'FinanceTreasureSpController@view')->name('treasure.sp.view');
        Route::post('finance/treasure-sp/historyjs', 'FinanceTreasureSpController@historyjs')->name('treasure.sp.historyjs');
        Route::post('finance/treasure-sp/add', 'FinanceTreasureSpController@add')->name('treasure.sp.add');
        Route::post('finance/treasure-sp/add-input', 'FinanceTreasureSpController@add_input')->name('treasure.sp.add.input');
        Route::post('finance/treasure-sp/approve', 'FinanceTreasureSpController@approve')->name('treasure.sp.approve');

        // General Journal
        Route::get('/finance/general-journal', 'FinanceGeneralJournal@index')->name('gj.index');
        Route::post('/finance/general-journal', 'FinanceGeneralJournal@add')->name('gj.add');
        Route::get('/finance/general-journal/delete/{id?}', 'FinanceGeneralJournal@delete')->name('gj.delete');
        Route::get('/finance/general-journal/find/{md5?}', 'FinanceGeneralJournal@find')->name('gj.find');
        Route::post('/finance/general-journal/edit', 'FinanceGeneralJournal@edit')->name('gj.edit');
        Route::post('/finance/general-journal/approve', 'FinanceGeneralJournal@approve')->name('gj.approve');

        //Forum
        Route::get('/general/forum','ForumController@index')->name('forum.index');
        Route::post('/general/forum','ForumController@storeForum')->name('forum.store');
        Route::get('/general/forum/topic/{id?}','ForumController@getTopic')->name('forum.topic');
        Route::get('/general/forum/topic/posts/{id?}','ForumController@getComments')->name('forum.topic.post');
        Route::post('/general/forum/topic','ForumController@storeTopic')->name('forum.topic.store');
        Route::get('/general/forum/topic/forum/{id}/{id_forum}','ForumController@deleteTopic')->name('forum.topic.delete');
        Route::get('/general/forum/topicAjax/{id?}','ForumController@getTopicAjax')->name('forum.topicAjax');
        Route::post('/general/forum/comment','ForumController@storePost')->name('forum.storepost');
        Route::get('/general/forum/comment/delete/{id}/{id_topik}','ForumController@deletePosts')->name('forum.deletepost');

        //MoM
        Route::get('/general/mom','MOMController@index')->name('mom.index');
        Route::get('/general/momAjax','MOMController@getMtgAjax')->name('mom.get');
        Route::get('/general/momAttendance/{id?}','MOMController@getAbsence')->name('mom.getAbsence');
        Route::get('/general/momMom/{id?}','MOMController@getMom')->name('mom.getMom');
        Route::post('/general/mom','MOMController@storeMain')->name('mom.store');
        Route::post('/general/mom/signatureSave','MOMController@signatureSave')->name('mom.sign.save');
        Route::post('/general/mom/signatureFileSave','MOMController@signatureFileSave')->name('mom.file.save');
        Route::get('/general/mom/detail/{id?}','MOMController@getDetail')->name('mom.detail');
        Route::get('/general/mom/actionprogress/{id?}','MOMController@setActionProgress')->name('mom.action.progress');
        Route::get('/general/mom/delete/{id?}','MOMController@deleteMain')->name('mom.delete.main');
        Route::get('/general/mom/delete/attd/{id}/{id_main}','MOMController@deletAttd')->name('mom.delete.attd');
        Route::get('/general/mom/delete/delMOM/{id}/{id_main}','MOMController@deletDelMOM')->name('mom.delete.delMOM');
        Route::post('/general/mom/detail/storeMOM','MOMController@storeMOM')->name('mom.detail.storeMOM');
        Route::post('/general/mom/detail/updateMOM','MOMController@updateMOM')->name('mom.detail.updateMOM');
        Route::get('/general/mom/print/{id?}','MOMController@printMv')->name('mom.printMv');

        //Documents
        Route::get('/general/documents', 'GeneralDocumentsController@index')->name('general.doc.index');
        Route::get('/general/documents/delete/{id?}', 'GeneralDocumentsController@delete')->name('general.doc.delete');
        Route::post('/general/documents/add', 'GeneralDocumentsController@add')->name('general.doc.add');
        Route::post('/general/documents/list', 'GeneralDocumentsController@list')->name('general.doc.list');

        //Marketing Documents
        Route::get('/marketing/documents', 'MarketingDocumentsController@index')->name('marketing.doc.index');
        Route::get('/marketing/documents/delete/{id?}', 'MarketingDocumentsController@delete')->name('marketing.doc.delete');
        Route::post('/marketing/documents/add', 'MarketingDocumentsController@add')->name('marketing.doc.add');
        Route::post('/marketing/documents/list', 'MarketingDocumentsController@list')->name('marketing.doc.list');

        //asset vehicles
        Route::get('/higher-authority/vehicles', 'HAVehiclesController@index')->name("ha.ve.index");
        Route::get('/higher-authority/vehicles/paper/js', 'HAVehiclesController@papers_js')->name("ha.ve.paper.js");
        Route::get('/higher-authority/vehicles/vehicles/js', 'HAVehiclesController@vehicles_js')->name("ha.ve.vehicle.js");
        Route::get('/higher-authority/vehicles/find/vehicle/{id?}', 'HAVehiclesController@edit_vehicle')->name("ha.ve.find.vehicle");
        Route::get('/higher-authority/vehicles/find/paper/{id?}', 'HAVehiclesController@edit_paper')->name("ha.ve.find.paper");
        Route::get('/higher-authority/vehicles/find/maintenance/{id?}', 'HAVehiclesController@edit_maintenance')->name("ha.ve.find.maintenance");
        Route::get('/higher-authority/vehicles/delete/category/{id?}', 'HAVehiclesController@delete_category')->name("ha.ve.delete.category");
        Route::get('/higher-authority/vehicles/delete/vehicle/{id?}', 'HAVehiclesController@delete_vehicle')->name("ha.ve.delete.vehicle");
        Route::get('/higher-authority/vehicles/delete/paper/{id?}', 'HAVehiclesController@delete_paper')->name("ha.ve.delete.paper");
        Route::get('/higher-authority/vehicles/delete/maintenance/{id?}', 'HAVehiclesController@delete_maintenance')->name("ha.ve.delete.maintenance");
        Route::get('/higher-authority/vehicles/view/{id?}', 'HAVehiclesController@view_vehicle')->name("ha.ve.view.vehicle");
        Route::post('/higher-authority/vehicles/add-category', 'HAVehiclesController@add_category')->name('ha.ve.add.category');
        Route::post('/higher-authority/vehicles/add-paper', 'HAVehiclesController@add_paper')->name('ha.ve.add.paper');
        Route::post('/higher-authority/vehicles/update-paper', 'HAVehiclesController@update_paper')->name('ha.ve.update.paper');
        Route::post('/higher-authority/vehicles/upload-paper', 'HAVehiclesController@upload_paper')->name('ha.ve.upload.paper');
        Route::post('/higher-authority/vehicles/add-vehicle', 'HAVehiclesController@add_vehicle')->name('ha.ve.add.vehicle');
        Route::post('/higher-authority/vehicles/update-vehicle', 'HAVehiclesController@update_vehicle')->name('ha.ve.update.vehicle');
        Route::post('/higher-authority/vehicles/add-maintenance', 'HAVehiclesController@add_maintenance')->name('ha.ve.add.maintenance');

        //Profit & Loss
        Route::get('/finance/profit-loss', 'FinanceProfitLossController@indexPL')->name('pl.index');
        Route::post('/finance/profit-loss', 'FinanceProfitLossController@indexPL')->name('pl.index');
        Route::get('/finance/profit-loss-list', 'FinanceProfitLossController@list')->name('pl.list');
        Route::post('/finance/profit-loss/setting', 'FinanceProfitLossController@setting')->name('pl.setting');
        Route::post('/finance/profit-loss/find', 'FinanceProfitLossController@index')->name('pl.find');
        Route::post('/finance/profit-loss/update', 'FinanceProfitLossController@update')->name('pl.update');


        //Cashbond
        Route::get('/general/cashbond','GeneralCashbond@index')->name('cashbond.index');
        Route::post('/general/cashbond/store', 'GeneralCashbond@addCashbond')->name('cashbond.add');
        Route::get('/general/cashbond/detail/{id}','GeneralCashbond@getDetail')->name('cashbond.detail');
        Route::post('/general/cashbond/addCashIn', 'GeneralCashbond@addCashIn')->name('cashbond.addCashIn');
        Route::post('/general/cashbond/addCashOut', 'GeneralCashbond@addCashOut')->name('cashbond.addCashOut');
        Route::post('/general/cashbond/RAppr', 'GeneralCashbond@RAppr')->name('cashbond.RAppr');
        Route::get('/general/cashbond/delete-detail/{id}/{id_cb}','GeneralCashbond@deleteDetail')->name('cashbond.deleteDetail');
        Route::get('/general/cashbond/delete/{id}','GeneralCashbond@delete')->name('cashbond.delete');
        Route::get('/general/cashbond/getDetRA/{id}/{who?}','GeneralCashbond@getDetRA')->name('cashbond.getDetRA');

        //Budget Request
        Route::get('/finance/budget-request', 'FinanceBudgetRequestController@index')->name('finance.br.index');
        Route::post('/finance/budget-request/list', 'FinanceBudgetRequestController@list_item')->name('finance.br.list');
        Route::get('/finance/budget-request/delete/{id?}', 'FinanceBudgetRequestController@delete')->name('finance.br.delete');
        Route::get('/finance/budget-request/input-amount/{action?}/{id}', 'FinanceBudgetRequestController@input_amount')->name('finance.br.input');
        Route::get('/finance/budget-request/delete-entry/{id?}', 'FinanceBudgetRequestController@delete_entry')->name('finance.br.delete_entry');
        Route::get('/finance/budget-request/appr/{action?}/{id?}', 'FinanceBudgetRequestController@appr')->name('finance.br.appr');
        Route::post('/finance/budget-request/request', 'FinanceBudgetRequestController@post_request')->name('finance.br.post_request');
        Route::post('/finance/budget-request/entry', 'FinanceBudgetRequestController@post_entry')->name('finance.br.post_entry');
        Route::post('/finance/budget-request/approve', 'FinanceBudgetRequestController@approve')->name('finance.br.approve');
        Route::get('/finance/budget-request/check/{id?}', 'FinanceBudgetRequestController@check')->name('finance.br.check');

        //filling document
        Route::get('/finance/filling-document', 'FinanceFillingDocumentController@index')->name('finance.fd.index');


        //Reimburse
        Route::get('/general/reimburse','GeneralReimburse@index')->name('reimburse.index');
        Route::post('/general/reimburse/store', 'GeneralReimburse@addReimburse')->name('reimburse.add');
        Route::get('/general/reimburse/detail/{id}','GeneralReimburse@getDetail')->name('reimburse.detail');
        Route::post('/general/reimburse/addCashOut', 'GeneralReimburse@addCashOut')->name('reimburse.addCashOut');
        Route::get('/general/reimburse/delete/{id}/detail/{id_cb}','GeneralReimburse@deleteDetail')->name('reimburse.deleteDetail');
        Route::get('/general/reimburse/delete/{id}','GeneralReimburse@delete')->name('reimburse.delete');
        Route::get('/general/reimburse/getDetRA/{id}/{who?}','GeneralReimburse@getDetRA')->name('reimburse.getDetRA');
        Route::post('/general/reimburse/RAppr', 'GeneralReimburse@RAppr')->name('reimburse.RAppr');

        // // Items
        // Route::get('/asset/items/item_code','AssetItemsController@itemCodeFunction')->name('items.itemCodeFunction');
        // Route::get('/asset/items/list', 'AssetItemsController@indexInventory')->name('items.inventory');
        // Route::get('/asset/items/list/withcategory/{category?}', 'AssetItemsController@index')->name('items.index');
        // Route::post('/asset/items', 'AssetItemsController@addItem')->name('items.add');
        // Route::post('/asset/items/find', 'AssetItemsController@find_item')->name('items.find');
        // Route::post('/asset/items/edit', 'AssetItemsController@edit_item')->name('items.edit');
        // Route::post('/asset/items/delete', 'AssetItemsController@delete')->name('items.delete');
        // Route::get('/asset/items/revision', 'AssetItemsController@revision')->name('items.revision');
        // Route::get('/asset/items/revision/{id}', 'AssetItemsController@revision_detail')->name('items.revision_detail');
        // Route::post('/asset/items/revision/update', 'AssetItemsController@revision_update')->name('items.revision_update');
        // Route::post('/asset/items/revision/delete', 'AssetItemsController@revision_delete')->name('items.revision_delete');
        // Route::get('/asset/items/list/warehouse/list/{id_wh}', 'AssetItemsController@getItemWh')->name('items.warehouses');
        // Route::get('/asset/items/transaction/find/{id?}', 'AssetItemsController@find_transaction')->name('items.find_transaction');

        // //category
        // Route::get('/asset/items','AssetNewCategoryController@index')->name('category.index');
        // Route::get('/asset/items/category','AssetNewCategoryController@getCategory')->name('category.get');
        // Route::post('/asset/items/category/update','AssetNewCategoryController@update')->name('category.update');
        // Route::post('/asset/items/category/store','AssetNewCategoryController@store')->name('category.store');
        // Route::get('/asset/items/category/{id}/del','AssetNewCategoryController@delete')->name('category.del');
        // Route::get('/asset/items/category/cari','AssetNewCategory@loadData')->name('category.cari');

        // //Classification
        // Route::get('/asset/items/classification/{category?}','AssetItemsClassificationController@index')->name('item_class.index');
        // Route::get('/asset/items/classification/getclassification/{id}','AssetItemsClassificationController@getClassification')->name('item_class.getclass');
        // Route::post('/asset/items/classification/store','AssetItemsClassificationController@store')->name('item_class.store');
        // Route::post('/asset/items/classification/update','AssetItemsClassificationController@update')->name('item_class.update');
        // Route::get('/asset/items/classification/delete/{id}','AssetItemsClassificationController@delete')->name('item_class.delete');

        // Items
        Route::get('/asset/items/last-input','AssetItemsController@last_input')->name('items.last.input');
        Route::post('/asset/items/last-input/list','AssetItemsController@last_input_list')->name('items.last.input.list');
        Route::get('/asset/items/list/by-class/{class?}', 'AssetItemsController@get_items_class')->name('items.list.class');
        Route::get('/asset/items/js', 'AssetItemsController@get_item_js')->name("items.js");

        Route::get('/asset/items/item_code','AssetItemsController@itemCodeFunction')->name('items.itemCodeFunction');
        Route::get('/asset/items/list', 'AssetItemsController@indexInventory')->name('items.inventory');
        Route::get('/asset/items/list/withcategory/{category?}/{classification?}', 'AssetItemsController@index')->name('items.index');
        Route::get('/asset/items/list/class/{category?}','AssetItemsController@indexClassification')->name('items.class.index');
        Route::post('/asset/items/add', 'AssetItemsController@addItem')->name('items.add');
        Route::post('/asset/items/find', 'AssetItemsController@find_item')->name('items.find');
        Route::post('/asset/items/edit', 'AssetItemsController@edit_item')->name('items.edit');
        Route::post('/asset/items/delete', 'AssetItemsController@delete')->name('items.delete');
        Route::get('/asset/items/revision/list/{category?}/{classification?}', 'AssetItemsController@revision')->name('items.revision');
        Route::get('/asset/items/revision/detail/{id}', 'AssetItemsController@revision_detail')->name('items.revision_detail');
        Route::post('/asset/items/revision/update', 'AssetItemsController@revision_update')->name('items.revision_update');
        Route::post('/asset/items/revision/delete', 'AssetItemsController@revision_delete')->name('items.revision_delete');
        Route::get('/asset/items/list/warehouse/list/{id_wh}', 'AssetItemsController@getItemWh')->name('items.warehouses');
        Route::get('/asset/items/transaction/find/{id?}', 'AssetItemsController@find_transaction')->name('items.find_transaction');

        //category
        Route::get('/asset/items','AssetNewCategoryController@index')->name('category.index');
        Route::get('/asset/items/category','AssetNewCategoryController@getCategory')->name('category.get');
        Route::post('/asset/items/category/update','AssetNewCategoryController@update')->name('category.update');
        Route::post('/asset/items/category/store','AssetNewCategoryController@store')->name('category.store');
        Route::get('/asset/items/category/{id}/del','AssetNewCategoryController@delete')->name('category.del');
        Route::get('/asset/items/category/cari','AssetNewCategory@loadData')->name('category.cari');
        Route::post('/asset/items','AssetNewCategoryController@search')->name('category.search');


        //category inventory
        Route::get('/asset/items-inventory','AssetCategoryInventoryController@index')->name('categoryinventory.index');
        Route::get('/asset/items-inventory/category','AssetCategoryInventoryController@getCategory')->name('categoryinventory.get');
        Route::post('/asset/items-inventory/category/update','AssetCategoryInventoryController@update')->name('categoryinventory.update');
        Route::post('/asset/items-inventory/category/store','AssetCategoryInventoryController@store')->name('categoryinventory.store');
        Route::get('/asset/items-inventory/category/{id}/del','AssetCategoryInventoryController@delete')->name('categoryinventory.del');
        Route::get('/asset/items-inventory/category/cari','AssetCategoryInventoryController@loadData')->name('categoryinventory.cari');
        Route::post('/asset/items-inventory','AssetCategoryInventoryController@search')->name('categoryinventory.search');
        Route::get('/asset/items-inventory/list/class/{category?}','AssetCategoryInventoryController@indexClassification')->name('itemsinventory.class.index');
        Route::get('/asset/items-inventory/storages', 'AssetItemsController@itemWh')->name('items.wh');


        //Classification
        Route::get('/asset/items/classification/{category?}','AssetItemsClassificationController@index')->name('item_class.index');
        Route::get('/asset/items/classification/getclassification/{id?}/{class_id?}','AssetItemsClassificationController@getClassification')->name('item_class.getclass');
        Route::post('/asset/items/classification/store','AssetItemsClassificationController@store')->name('item_class.store');
        Route::post('/asset/items/classification/update','AssetItemsClassificationController@update')->name('item_class.update');
        Route::get('/asset/items/classification/delete/{id}','AssetItemsClassificationController@delete')->name('item_class.delete');


        //Items Approval
        Route::get('/asset/items/approval', 'AssetItemsController@items_approval')->name('items.approval');
        Route::get('/asset/items/approval/get/{id?}', 'AssetItemsController@items_approval_get')->name('items.approval.get');
        Route::get('/asset/items/approval/class/get/{category?}', 'AssetItemsController@items_approval_class_get')->name('items.approval.class.get');
        Route::post('/asset/items/approval/get-code', 'AssetItemsController@items_approval_get_code')->name('items.approval.get.code');
        Route::post('/asset/items/approval/update', 'AssetItemsController@items_approval_update')->name('items.approval.update');


        //classification inventory

        Route::get('/asset/items-inventory/classification/{category?}','AssetItemsClassificationInventoryController@index')->name('item_classinventory.index');
        Route::get('/asset/items-inventory/classification/getclassification/{id?}/{class_id?}','AssetItemsClassificationInventoryController@getClassification')->name('item_classinventory.getclass');
        Route::post('/asset/items-inventory/classification/store','AssetItemsClassificationInventoryController@store')->name('item_classinventory.store');
        Route::post('/asset/items-inventory/classification/update','AssetItemsClassificationInventoryController@update')->name('item_classinventory.update');
        Route::get('/asset/items-inventory/classification/delete/{id}','AssetItemsClassificationInventoryController@delete')->name('item_classinventory.delete');
        Route::get('/asset/items-inventory/list/{category?}/{classification?}', 'AssetItemsClassificationInventoryController@indexInventory')->name('itemsinventory.inventory');
        Route::get('/asset/items-inventory/detail/{id?}', 'AssetCategoryInventoryController@detail')->name("itemsInventory.detail");
        Route::post('/asset/items-inventory/find', 'AssetCategoryInventoryController@find')->name("itemsInventory.find");
        Route::post('/asset/items-inventory/add-quantity', 'AssetCategoryInventoryController@add_qty')->name('itemsInventory.add.qty');

        //Legal
        Route::get('/asset/legal-document', 'AssetLegalController@index')->name('asset.legal.index');
        Route::get('/asset/legal-document/detail/{id?}', 'AssetLegalController@detail')->name('asset.legal.detail');
        Route::post('/asset/legal-document/add', 'AssetLegalController@add')->name('asset.legal.add');
        Route::post('/asset/legal-document/update', 'AssetLegalController@update')->name('asset.legal.update');
        Route::post('/asset/legal-document/upload', 'AssetLegalController@upload')->name('asset.legal.upload');
        Route::get('/asset/legal-document/delete/{id?}', 'AssetLegalController@delete')->name('asset.legal.delete');
        Route::get('/asset/legal-document/image/{id?}', 'AssetLegalController@image')->name('asset.legal.image');


        // INVOICE IN
        Route::get('/finance/invoice-in/', 'FinanceInvoiceIn@index')->name('inv_in.index');
        Route::get('/finance/invoice-in/view/{id?}', 'FinanceInvoiceIn@view')->name('inv_in.view');
        Route::post('/finance/invoice-in/delete/', 'FinanceInvoiceIn@delete')->name('inv_in.delete');
        Route::post('/finance/invoice-in/delete_pay', 'FinanceInvoiceIn@delete_pay')->name('inv_in.delete_pay');
        Route::post('/finance/invoice-in/search', 'FinanceInvoiceIn@search_paper')->name('inv_in.search');
        Route::post('/finance/invoice-in/add', 'FinanceInvoiceIn@add')->name('inv_in.add');
        Route::post('/finance/invoice-in/pay', 'FinanceInvoiceIn@pay')->name('inv_in.pay');
        Route::post('/finance/invoice-in/duedate', 'FinanceInvoiceIn@duedate')->name('inv_in.duedate');
        Route::post('/finance/invoice-in/items', 'FinanceInvoiceIn@list_items')->name('inv_in.items');
        Route::get('/finance/invoice-in/tc/{type?}', 'FinanceInvoiceIn@get_tc')->name('inv_in.tc');
        ROute::post('/finance/invoice-in/tc/assign', 'FinanceInvoiceIn@assign_tc')->name("inv_in.assign_tc");

        // Account Receivable
        Route::get('/finance/invoice-out', 'FinanceAccountReceivable@index')->name('ar.index');
        Route::get('/finance/invoice-out/pl/{id?}', 'FinanceAccountReceivable@getProjectLeads')->name('ar.getpl');
        Route::get('/finance/invoice-out/delete/{id?}', 'FinanceAccountReceivable@delete')->name('ar.delete');
        Route::get('/finance/invoice-out/delete-entry/{id?}', 'FinanceAccountReceivable@delete_entry')->name('ar.delete_entry');
        Route::get('/finance/invoice-out/edit-entry/{id}', 'FinanceAccountReceivable@edit_entry')->name('ar.edit_entry');
        Route::get('/finance/invoice-out/view/{id}', 'FinanceAccountReceivable@view')->name('ar.view');
        Route::get('/finance/invoice-out/input-entry/{id}', 'FinanceAccountReceivable@input_entry')->name('ar.input_entry');
        Route::get('/finance/invoice-out/view-entry/{id}/{act}', 'FinanceAccountReceivable@view_entry')->name('ar.view_entry');
        Route::get('/finance/invoice-out/check-inv/{id?}', 'FinanceAccountReceivable@check_inv')->name('ar.check_inv');
        Route::get('/finance/invoice-out/find/{id?}', 'FinanceAccountReceivable@find')->name('ar.find');
        Route::post('/finance/invoice-out/add', 'FinanceAccountReceivable@add')->name('ar.add');
        Route::post('/finance/invoice-out/add-entry', 'FinanceAccountReceivable@addEntry')->name('ar.addEntry');
        Route::post('/finance/invoice-out/add-input', 'FinanceAccountReceivable@add_input')->name('ar.add_input');
        Route::post('/finance/invoice-out/update', 'FinanceAccountReceivable@update')->name('ar.update');
        Route::post('/finance/invoice-out/edit-input', 'FinanceAccountReceivable@edit_input')->name('ar.edit_input');
        Route::post('/finance/invoice-out/appr-manager', 'FinanceAccountReceivable@appr_manager')->name('ar.appr_manager');
        Route::post('/finance/invoice-out/appr-finance', 'FinanceAccountReceivable@appr_finance')->name('ar.appr_finance');
        Route::post('/finance/invoice-out/revise', 'FinanceAccountReceivable@revise')->name('ar.revise');
        Route::get('/finance/invoice-out/revise-approve/{id?}', 'FinanceAccountReceivable@revise_approve')->name('ar.revise.approve');
        Route::get('/finance/invoice-out/list', 'FinanceAccountReceivable@_list')->name('ar.list');

        //sanction
        Route::get('/hrd/deduction','HrdSanctionController@index')->name('sanction.index');
        Route::post('/hrd/deduction/store', 'HrdSanctionController@addDeduction')->name('sanction.store');
        Route::get('/hrd/deduction/{id}/delete','HrdSanctionController@delete')->name('sanction.delete');
        Route::post('/hrd/deduction/{id}/approve','HrdSanctionController@approveDeduction')->name('sanction.approve');

        //salary_financing
        Route::get('/finance/salary_financing','FinanceSPController@getSalaryFinancing')->name('salfin.index');
        Route::get('/finance/salary_financing/stat','FinanceSPController@getSalaryFinancingStat')->name('salfin.stat');
        Route::post('/finance/salary_financing','FinanceSPController@paySalaryFinancing')->name('salfin.pay');
        Route::get('/finance/salary_financing/delete/{id?}', 'FinanceSPController@delete_salary_financing')->name('salfin.delete');

        //BPJS Kes
        Route::get("/finance/bpjs-kes", "HrdEmployeeController@bpjs")->name('finance.bpjs');
        Route::get("/finance/bpjs-tk", "HrdEmployeeController@bpjs_tk")->name('finance.bpjs_tk');

        //SCHEDULE PAYMENT
        Route::get('/finance/schedule-payment', 'FinanceSPController@index')->name('sp.index');
        Route::get('/finance/schedule-payment/{date?}', 'FinanceSPController@pay')->name('sp.pay');
        Route::post('/finance/schedule-payment', 'FinanceSPController@index')->name('sp.index');
        Route::post('/finance/schedule-payment/confirm', 'FinanceSPController@confirm')->name('sp.confirm');
        Route::post('/finance/schedule-payment/edit-date', 'FinanceSPController@edit_date')->name('sp.edit_date');
        Route::post('/finance/schedule-payment/history', 'FinanceSPController@history')->name('sp.history');
        Route::post('/finance/schedule-payment/update-util', 'FinanceSPController@update_util')->name('sp.update.util');

        //Sub Cost
        Route::get('/marketing/subcost','MarketingSubcostController@index')->name('subcost.index');
        Route::get('/marketing/subcost/{id}/done','MarketingSubcostController@submitDone')->name('subcost.done');
        Route::get('/marketing/subcost/{id}/detail','MarketingSubcostController@getDetail')->name('subcost.detail');
        Route::post('/marketing/subcost/addCash', 'MarketingSubcostController@addCash')->name('subcost.addCash');
        Route::get('/marketing/subcost/{id}/delete/{id_detail}','MarketingSubcostController@deleteSubcostDetail')->name('subcost.delete.detail');
        Route::get('/marketing/subcost/{id}/approve/{id_detail}/{type}','MarketingSubcostController@submitApprove')->name('subcost.approve');
        Route::post('/marketing/subcost/apprFin','MarketingSubcostController@submitApproveFin')->name('subcost.approveFin');

        Route::get('/general/crewloc/maps','GeneralCrewLocationController@maps')->name('crewloc.maps');
        Route::get('/general/crewloc/maps_js','GeneralCrewLocationController@maps_js')->name('crewloc.maps_js');
        Route::get('/general/crewloc','GeneralCrewLocationController@index')->name('crewloc.index');
        Route::get('/general/crewloc/markers','GeneralCrewLocationController@markers')->name('crewloc.markers');
        Route::get('/general/crewloc/crew/{id?}','GeneralCrewLocationController@crew')->name('crewloc.crew');
        Route::post('/general/crewloc/storeplan','GeneralCrewLocationController@addToPlan')->name('crewloc.storeplan');

        //BP
        Route::get('/marketing/bp','MarketingBpController@index')->name('bp.index');
        Route::post('/marketing/bp/store','MarketingBpController@addBP')->name('bp.add');
        Route::get('/marketing/bp/{id}/findiv', 'MarketingBpController@getFinDiv')->name('bp.findiv');
        Route::get('/marketing/bp/view/{id}', 'MarketingBpController@view')->name('bp.view');
        Route::post('/marketing/bp/findivappr', 'MarketingBpController@finDivAppr')->name('bp.finDivAppr');
        Route::get('/marketing/bp/{id}/findiv/{code}', 'MarketingBpController@getDirAppr')->name('bp.getDirAppr');
        Route::post('/marketing/bp/submitAppr','MarketingBpController@submitAppr')->name('bp.submitappr');
        Route::post('/marketing/bp/submitBondR','MarketingBpController@bondR')->name('bp.bondR');
        Route::get('/marketing/bp/delete/{id?}','MarketingBpController@delete')->name('bp.delete');

        //Finance Loan
        Route::get('/finance/loan', 'FinanceLoanController@index')->name('loan.index');
        Route::get('/finance/loan/{id}', 'FinanceLoanController@detail')->name('loan.detail');
        Route::post('/finance/loan/add', 'FinanceLoanController@add')->name('loan.add');
        Route::post('/finance/loan/save-plan', 'FinanceLoanController@save_plan')->name('loan.save_plan');
        Route::post('/finance/loan/update-plan', 'FinanceLoanController@update_plan')->name('loan.update_plan');
        Route::get('/finance/loan/edit-plan/{id}', 'FinanceLoanController@edit_plan')->name('loan.edit_plan');
        Route::post('/finance/loan/delete', 'FinanceLoanController@delete')->name('loan.delete');

        //Finance Leasing
        Route::get('/finance/leasing', 'FinanceLeasingController@index')->name('leasing.index');
        Route::get('/finance/leasing/{id}', 'FinanceLeasingController@detail')->name('leasing.detail');
        Route::post('/finance/leasing/add', 'FinanceLeasingController@add')->name('leasing.add');
        Route::post('/finance/leasing/save-plan', 'FinanceLeasingController@save_plan')->name('leasing.save_plan');
        Route::post('/finance/leasing/update-plan', 'FinanceLeasingController@update_plan')->name('leasing.update_plan');
        Route::get('/finance/leasing/edit-plan/{id}', 'FinanceLeasingController@edit_plan')->name('leasing.edit_plan');
        Route::post('/finance/leasing/delete', 'FinanceLeasingController@delete')->name('leasing.delete');

        //Finance Utilization
        Route::get('/finance/utilization', 'FinanceUtilizationController@index')->name('util.index');
        Route::get('/finance/utilization/lists', 'FinanceUtilizationController@get_data')->name('util.lists');
        Route::get('/finance/utilization/update-detail', 'FinanceUtilizationController@update_detail')->name('util.update_detail');
        Route::get('/finance/utilization/get-date/{date?}', 'FinanceUtilizationController@getDateMonth')->name('util.getdate');
        Route::get('/finance/utilization/update-status/{id?}', 'FinanceUtilizationController@update_status')->name('util.update_status');
        Route::get('/finance/utilization/criteria/delete/{id?}', 'FinanceUtilizationController@deleteCriteria')->name('util.delete.criteria');
        Route::get('/finance/utilization/instance/delete/{id?}', 'FinanceUtilizationController@deleteInstance')->name('util.delete.instance');
        Route::get('/finance/utilization/view/{id}', 'FinanceUtilizationController@view')->name('util.view');
        Route::post('/finance/utilization/criteria', 'FinanceUtilizationController@addCriteria')->name('util.add.criteria');
        Route::post('/finance/utilization/add', 'FinanceUtilizationController@add')->name('util.add');
        Route::post('/finance/utilization/delete', 'FinanceUtilizationController@delete')->name('util.delete');
        Route::post('/finance/utilization/change-amount', 'FinanceUtilizationController@change_amount')->name('util.change_amount');
        Route::post('/finance/utilization/change-amount-instance', 'FinanceUtilizationController@change_amount_instance')->name('util.change_amount_instance');

        //Finance Tax
        Route::get('/finance/tax', 'FinanceTaxController@index')->name('tax.index');
        Route::post('/finance/tax/data', 'FinanceTaxController@get_data')->name('tax.get_data');

        //Finance Business
        Route::get('/finance/business', 'FinanceBusinessController@index')->name('business.index');
        Route::get('/finance/delete/{id?}', 'FinanceBusinessController@delete')->name('business.delete');
        Route::get('/finance/business/detail/{id}', 'FinanceBusinessController@detail')->name('business.detail');
        Route::get('/finance/business/investor/detail/{id}', 'FinanceBusinessController@investor')->name('business.investor');
        Route::get('/finance/business/investor/delete', 'FinanceBusinessController@deleteInvestor')->name('business.deleteInvestor');
        Route::get('/finance/business/investor/delete-investment', 'FinanceBusinessController@deleteInvesment')->name('business.deleteInvesment');
        Route::get('/finance/business/edit/{id?}', 'FinanceBusinessController@edit')->name('business.edit');
        Route::get('/finance/business/pay/{id?}', 'FinanceBusinessController@pay')->name('business.pay');
        Route::get('/finance/business/investor/pay', 'FinanceBusinessController@investorPay')->name('business.investorPay');
        Route::get('/finance/business/print/{id?}', 'FinanceBusinessController@print')->name('business.print');
        Route::post('/finance/business/investor/transfer', 'FinanceBusinessController@transfer')->name('business.transfer');
        Route::get('/finance/business/balance-investor', 'FinanceBusinessController@balance')->name('business.balance');
        Route::post('/finance/business/balance/search', 'FinanceBusinessController@balance_search')->name('business.balance_search');

        Route::get('/finance/business/detail-close/{id?}', 'FinanceBusinessController@detail_close')->name('business.detail_close');
        Route::get('/finance/business/detail/edit/{id?}', "FinanceBusinessController@detail_edit")->name('business.detail_edit');
        Route::post('/finance/business/detail/edit/{id?}', "FinanceBusinessController@detail_edit")->name('business.detail_edit_post');

        Route::post('/finance/business/add', 'FinanceBusinessController@add')->name('business.add');
        Route::post('/finance/business/update', 'FinanceBusinessController@update')->name('business.update');
        Route::post('/finance/business/pay', 'FinanceBusinessController@payConfirm')->name('business.payConfirm');
        Route::post('/finance/business/investor/add', 'FinanceBusinessController@addInvestor')->name('business.addInvestor');
        Route::post('/finance/business/investor/update-rate', 'FinanceBusinessController@updateRate')->name('business.updateRate');
        Route::post('/finance/business/investor/update-amount', 'FinanceBusinessController@updateAmount')->name('business.updateAmount');
        Route::post('/finance/business/investor/add-investment', 'FinanceBusinessController@addInvesment')->name('business.addInvesment');
        Route::post('/finance/business/investor/save-text', 'FinanceBusinessController@updateText')->name('business.updateText');
        Route::get('/finance/business/investor/edit/{id}', "FinanceBusinessController@investorEdit")->name('business.investor.edit');
        Route::get('/finance/business/payment-schedule/', 'FinanceBusinessController@payment_schedule')->name('business.payment_schedule');
        Route::post('/finance/business/payment-search/', 'FinanceBusinessController@payment_search')->name('business.payment_search');

        Route::get('/finance/business/investors', 'FinanceBusinessController@investors')->name('business.investors');
        Route::post('/finance/business/investors', 'FinanceBusinessController@add_investors')->name('business.add_investors');

        Route::get('/finance/business/partners', 'FinanceBusinessController@partners')->name('business.partners');
        Route::post('/finance/business/partners', 'FinanceBusinessController@add_partners')->name('business.add_partners');
        Route::get('/finance/business/balance-partners', 'FinanceBusinessController@balance_partners')->name('business.balance.partners');
        Route::post('/finance/business/balance-partners', 'FinanceBusinessController@balance_partners_search')->name('business.balance.partners.search');

        Route::get("/finance/business/investors/list/{id?}", 'FinanceBusinessController@investors_list')->name("business.investors.list");
        Route::post("/finance/business/investors/list/add", 'FinanceBusinessController@investors_list_add')->name("business.investors.list.add");
        Route::post('/finance/business/investor/list/add-investment', 'FinanceBusinessController@investors_list_addInvesment')->name('business.investors.list.addInvesment');
        Route::get('/finance/business/investor/list/delete-investment/{id}/{index}', 'FinanceBusinessController@investors_list_deleteInvesment')->name('business.investors.list.deleteInvesment');
        Route::post('/finance/business/investor/list/save-text', 'FinanceBusinessController@investors_list_save_text')->name('business.investors.list.save.text');
        Route::post("/finance/business/investors/list/delete", 'FinanceBusinessController@investors_list_delete')->name("business.investors.list.delete");
        Route::post('/finance/business/investors/list/pay-list', "FinanceBusinessController@investors_pay_list")->name("business.investors.pay.list");
        Route::post('/finance/business/investors/list/pay', "FinanceBusinessController@investors_pay")->name("business.investors.pay");
        Route::post('/finance/business/investors/list/close-list', "FinanceBusinessController@investors_close_list")->name("business.investors.close.list");
        Route::post('/finance/business/investors/list/close', "FinanceBusinessController@investors_close")->name("business.investors.close");
        Route::post('/finance/business/investors/list/edit-investment', "FinanceBusinessController@investors_list_editInvestment")->name("business.investors.list.editInvestment");

        // Trading
        //supplier
        Route::get('/trading/supplier','TradingSupplierController@index')->name('trading.supplier.index');
        Route::get('/trading/supplier/{id}/edit','TradingSupplierController@edit')->name('trading.supplier.edit');
        Route::post('/trading/supplier/store','TradingSupplierController@storeSupplier')->name('trading.supplier.store');
        Route::post('/trading/supplier/update','TradingSupplierController@updateSupplier')->name('trading.supplier.update');
        Route::get('/trading/supplier/{id}/delete','TradingSupplierController@delete')->name('trading.supplier.delete');
        Route::post('/trading/supplier/uploadNDA','TradingSupplierController@uploadNDA')->name('trading.supplier.uploadNDA');

        //markets
        Route::get('/trading/markets','TradingMarketController@index')->name('trading.market.index');
        Route::post('/trading/store','TradingMarketController@store')->name('trading.market.store');
        Route::get('/trading/{id}/delete','TradingMarketController@delete')->name('trading.market.delete');
        Route::post('/trading/update','TradingMarketController@update')->name('trading.market.update');
        Route::post('/trading/add-js','TradingMarketController@add_js')->name('trading.market.add.js');
        Route::get('/trading/get-markets','TradingMarketController@get_markets')->name('trading.market.get.js');

        //products
        Route::get('/trading/products','TradingProductsController@index')->name('trading.products.index');
        Route::get('/trading/products/detail/{id?}','TradingProductsController@detail')->name('trading.products.detail');
        Route::post('/trading/products/add','TradingProductsController@add')->name('trading.products.add');
        Route::post('/trading/products/update','TradingProductsController@update')->name('trading.products.update');
        Route::get('/trading/products/autocomplete/{supplier?}','TradingProductsController@autocomplete')->name('trading.products.autocomplete');

        //orders
        Route::get('/trading/orders','TradingOrdersController@index')->name('trading.orders.index');
        Route::post('/trading/orders/add','TradingOrdersController@add')->name('trading.orders.add');
        Route::post('/trading/orders/final','TradingOrdersController@uploadFinal')->name('trading.orders.final');

        /*Technical Engineering*/
        //Equipment List
        Route::get('/technical-engineering/equipment-list', 'TeEquipmentListController@index')->name('te.el.index');
        Route::get('/technical-engineering/equipment-list/detail/{id}', 'TeEquipmentListController@detail')->name('te.el.detail');
        Route::get('/technical-engineering/equipment-list/delete-category/{id?}', 'TeEquipmentListController@deleteCategory')->name('te.el.deleteCategory');
        Route::post('/technical-engineering/equipment-list/add-category', 'TeEquipmentListController@addCategory')->name('te.el.addCategory');
        Route::post('/technical-engineering/equipment-list/update-category', 'TeEquipmentListController@updateCategory')->name('te.el.updateCategory');
        Route::get('/technical-engineering/equipment-list/items/{id?}/{type?}','TeEquipmentListController@items')->name('te.el.items');
        Route::get('/technical-engineering/equipment-list/items-detail/{id?}/','TeEquipmentListController@items_detail')->name('te.el.items_detail');
        Route::get('/technical-engineering/equipment-list/view-item/{id?}','TeEquipmentListController@view_item')->name('te.el.view_item');
        Route::post('/technical-engineering/equipment-list/maintenance/add', 'TeEquipmentListController@add_maintenance')->name('te.el.maintenance.add');
        Route::get("/technical-engineering/equipment-list/maintenance/delete/{id}", 'TeEquipmentListController@delete_maintenance')->name('te.el.maintenance.delete');

        Route::get('/technical-engineering/equipment-list/delete/{id?}', 'TeEquipmentListController@delete')->name('te.el.delete');
        Route::post('/technical-engineering/equipment-list/add', 'TeEquipmentListController@add')->name('te.el.add');
        Route::post('/technical-engineering/equipment-list/update', 'TeEquipmentListController@update')->name('te.el.update');
        Route::get('/technical-engineering/equipment-list/delete-file/{id?}/{type?}', 'TeEquipmentListController@deleteFile')->name('te.el.deleteFile');

        //Project Design
        Route::get('/technical-engineering/project-design', 'TeProjectDesignController@index')->name('te.pd.index');
        Route::get('/technical-engineering/project-design/detail/{id}', 'TeProjectDesignController@detail')->name('te.pd.detail');
        Route::get('/technical-engineering/project-design/delete-category/{id?}', 'TeProjectDesignController@deleteCategory')->name('te.pd.deleteCategory');
        Route::post('/technical-engineering/project-design/add-category', 'TeProjectDesignController@addCategory')->name('te.pd.addCategory');
        Route::post('/technical-engineering/project-design/update-category', 'TeProjectDesignController@updateCategory')->name('te.pd.updateCategory');
        Route::post('/technical-engineering/project-design/update-items', 'TeProjectDesignController@updateItems')->name('te.pd.updateItems');

        Route::get('/technical-engineering/project-design/delete/{id?}', 'TeProjectDesignController@delete')->name('te.pd.delete');
        Route::post('/technical-engineering/project-design/add', 'TeProjectDesignController@add')->name('te.pd.add');
        Route::post('/technical-engineering/project-design/update', 'TeProjectDesignController@update')->name('te.pd.update');
        Route::get('/technical-engineering/project-design/delete-file/{id?}/{type?}', 'TeProjectDesignController@deleteFile')->name('te.pd.deleteFile');
        Route::get('/technical-engineering/project-design/find-items/{id?}', 'TeProjectDesignController@findItems')->name('te.pd.findItems');

        //SWT
        Route::get('/technical-engineering/surface-welltesting', 'TeSwtController@index')->name('te.swt.index');
        Route::get('/technical-engineering/surface-welltesting/find/{id?}', 'TeSwtController@find')->name('te.swt.find');
        Route::get('/technical-engineering/surface-welltesting/items/{id?}', 'TeSwtController@items')->name('te.swt.items');
        Route::get('/technical-engineering/surface-welltesting/get-items/{id?}', 'TeSwtController@get_items')->name('te.swt.get_items');
        Route::get('/technical-engineering/surface-welltesting/delete/{id?}', 'TeSwtController@delete')->name('te.swt.delete');
        Route::post('/technical-engineering/surface-welltesting/add', 'TeSwtController@add')->name('te.swt.add');
        Route::post('/technical-engineering/surface-welltesting/update', 'TeSwtController@update')->name('te.swt.update');
        Route::post('/technical-engineering/surface-welltesting/update-items', 'TeSwtController@update_items')->name('te.swt.update_items');

        //SUBWT
        Route::get('/technical-engineering/subsurface-welltesting', 'TeSubwtController@index')->name('te.subwt.index');
        Route::get('/technical-engineering/subsurface-welltesting/find/{id?}', 'TeSubwtController@find')->name('te.subwt.find');
        Route::get('/technical-engineering/subsurface-welltesting/items/{id?}', 'TeSubwtController@items')->name('te.subwt.items');
        Route::get('/technical-engineering/subsurface-welltesting/get-items/{id?}', 'TeSubwtController@get_items')->name('te.subwt.get_items');
        Route::get('/technical-engineering/subsurface-welltesting/delete/{id?}', 'TeSubwtController@delete')->name('te.subwt.delete');
        Route::post('/technical-engineering/subsurface-welltesting/add', 'TeSubwtController@add')->name('te.subwt.add');
        Route::post('/technical-engineering/subsurface-welltesting/update', 'TeSubwtController@update')->name('te.subwt.update');
        Route::post('/technical-engineering/subsurface-welltesting/update-items', 'TeSubwtController@update_items')->name('te.subwt.update_items');

        //SLICKLINE
        Route::get('/technical-engineering/slickline', 'TeSlicklineController@index')->name('te.slickline.index');
        Route::get('/technical-engineering/slickline/find/{id?}', 'TeSlicklineController@find')->name('te.slickline.find');
        Route::get('/technical-engineering/slickline/items/{id?}', 'TeSlicklineController@items')->name('te.slickline.items');
        Route::get('/technical-engineering/slickline/get-items/{id?}', 'TeSlicklineController@get_items')->name('te.slickline.get_items');
        Route::get('/technical-engineering/slickline/delete/{id?}', 'TeSlicklineController@delete')->name('te.slickline.delete');
        Route::post('/technical-engineering/slickline/add', 'TeSlicklineController@add')->name('te.slickline.add');
        Route::post('/technical-engineering/slickline/update', 'TeSlicklineController@update')->name('te.slickline.update');
        Route::post('/technical-engineering/slickline/update-items', 'TeSlicklineController@update_items')->name('te.slickline.update_items');

        //Instrument Items
        Route::get('/techincal-engineering/items-instrument-details','TeInstrumentationController@index')->name('te.instrument.index');
        Route::get('/techincal-engineering/items-instrument-details/revision','TeInstrumentationController@revision')->name('te.instrument.revision');
        Route::get('/techincal-engineering/items-instrument-details/revision/detail/{id}','TeInstrumentationController@revision_detail')->name('te.instrument.revision_detail');
        Route::get('/techincal-engineering/items-instrument-details/getInstrumentList','TeInstrumentationController@getInstrumentList')->name('te.instrument.getInstrumentList');
        Route::post('/techincal-engineering/items-instrument-details/store','TeInstrumentationController@store')->name('te.instrument.store');
        Route::post('/techincal-engineering/items-instrument-details/update','TeInstrumentationController@update')->name('te.instrument.update');
        Route::post('/techincal-engineering/items-instrument-details/revision_approve','TeInstrumentationController@revision_approve')->name('te.revision_approve');
        Route::get('/techincal-engineering/items-instrument-details/delete/{id}','TeInstrumentationController@delete')->name('te.instrument.delete');

        //Test Equipment
        Route::get('/techincal-engineering/test-equipment','TeTestEqController@index')->name('te.testeq.index');
        Route::get('/techincal-engineering/test-equipment/revision','TeTestEqController@revision')->name('te.testeq.revision');
        Route::get('/techincal-engineering/test-equipment/revision/detail/{id}','TeTestEqController@revision_detail')->name('te.testeq.revision_detail');
        Route::get('/techincal-engineering/test-equipment/getInstrumentList','TeTestEqController@getInstrumentList')->name('te.testeq.getInstrumentList');
        Route::post('/techincal-engineering/test-equipment/store','TeTestEqController@store')->name('te.testeq.store');
        Route::post('/techincal-engineering/test-equipment/update','TeTestEqController@update')->name('te.testeq.update');
        Route::post('/techincal-engineering/test-equipment/revision_approve','TeTestEqController@revision_approve')->name('te.testeq.revision_approve');
        Route::get('/techincal-engineering/test-equipment/delete/{id}','TeTestEqController@delete')->name('te.testeq.delete');


        /*HIGHER AUTHORITY*/
        //PO WO Types
        Route::get('/higher-authority/po-wo-types', 'HAPoWoTypesController@index')->name('ha.powotypes.index');
        Route::get('/higher-authority/getTypes/{type?}/{id?}', 'HAPoWoTypesController@getTypes')->name('ha.powotypes.getTypes');

        Route::post('/higher-authority/add-po-type', 'HAPoWoTypesController@addPoType')->name('ha.powotypes.addPoType');
        Route::post('/higher-authority/add-wo-type', 'HAPoWoTypesController@addWoType')->name('ha.powotypes.addWoType');
        Route::post('/higher-authority/updateType', 'HAPoWoTypesController@updateType')->name('ha.powotypes.updateType');
        Route::post('/higher-authority/changeType', 'HAPoWoTypesController@changeType')->name('ha.powotypes.changeType');
        Route::get('/higher-authority/delete-type/{id?}/{type?}', 'HAPoWoTypesController@deleteType')->name('ha.powotypes.deleteType');

        //PO WO Validation
        Route::get('/hihger-authority/po-wo-validation', 'HAPoWoValidationController@index')->name('ha.powoval.index');
        Route::get('/hihger-authority/po-wo-validation/delete/{id?}', 'HAPoWoValidationController@delete')->name('ha.powoval.delete');
        Route::get('/hihger-authority/po-wo-validation/find/{type?}/{kode?}', 'HAPoWoValidationController@find')->name('ha.powoval.find');

        Route::post('/hihger-authority/po-wo-validation/addCode', 'HAPoWoValidationController@addCode')->name('ha.powoval.addCode');

        //password
        Route::get('/higher-authority/password-management', 'HAPasswordController@index')->name('ha.password.index');
        Route::get('/higher-authority/password-management/delete/{id}', 'HAPasswordController@delete')->name('ha.password.delete');
        Route::post('/higher-authority/password-management/create', 'HAPasswordController@create')->name('ha.password.create');

        //bank ceo
        Route::get('/dirut/bank-CEO','DirutBankCEOController@index')->name('bankceo.index');
        Route::get('/dirut/bank-CEO/{bank?}','DirutBankCEOController@getDetail')->name('bankceo.detail');
        Route::get('/dirut/bank-CEO/delete/{id}','DirutBankCEOController@delete')->name('bankceo.delete');
        Route::post('/dirut/bank-CEO/{bank?}','DirutBankCEOController@filterBankDetail')->name('bankceo.filter');
        Route::post('/dirut/bank-CEO/post/addtrans','DirutBankCEOController@addTrans')->name('bankceo.addTrans');

        //insurance ceo
        Route::get('/dirut/insurance-CEO','DirutInsCEOController@index')->name('insceo.index');
        Route::get('/dirut/insurance-CEO/get/{id}','DirutInsCEOController@getDetail')->name('insceo.detail');
        Route::get('/dirut/insurance-CEO/delete/{id}','DirutInsCEOController@delete')->name('insceo.delete');
        Route::get('/dirut/insurance-CEO/delete/file/{id}','DirutInsCEOController@deleteDetail')->name('insceo.deleteDetail');
        Route::post('/dirut/insurance-CEO/post/store','DirutInsCEOController@store')->name('insceo.store');
        Route::post('/dirut/insurance-CEO/post/files','DirutInsCEOController@saveFile')->name('insceo.saveFile');

        //needsec
        Route::get('/needsec/', 'NeedsecController@index')->name('needsec.index');
        Route::post('/needsec/confirmation', 'NeedsecController@confirmation')->name('needsec.confirmation');


        //pricelist
        Route::get('/procurement/pricelist','ProcurementPriceListController@index')->name('pricelist.index');

        //salarylist
        Route::get('/dirut/salarylist','SalaryListController@index')->name('salarylist.index');


        //warehouse
        Route::get('/asset/storages','AssetWarehouseController@index')->name('wh.index');
        Route::post('/asset/storages/store','AssetWarehouseController@store')->name('wh.store');
        Route::post('/asset/storages/update','AssetWarehouseController@update')->name('wh.update');
        Route::get('/asset/storages/{id}/delete','AssetWarehouseController@delete')->name('wh.delete');

//Delivery Order
        Route::get('/general/do','GeneralDOController@index')->name('do.index');
        Route::get('/general/do/wh-report/{id_wh}','GeneralDOController@getWarehouseReport')->name('do.getWarehouseReport');
        Route::post('/general/do/wh-report/{id_wh}','GeneralDOController@whReport')->name('do.whReport');
        Route::get('/general/do/detail/{type?}/{id}/','GeneralDOController@getDO')->name('do.detail');
        Route::get('/general/do/getWh','GeneralDOController@getWarehouse')->name('do.getWh');
        Route::post('/general/do/store','GeneralDOController@store')->name('do.add');
        Route::post('/general/do/edit','GeneralDOController@update')->name('do.edit');
        Route::post('/general/do/receive','GeneralDOController@updateGR')->name('do.receive');
        Route::get('/general/do/delete/{id}','GeneralDOController@deleteDO')->name('do.delete');
        Route::get('/general/do/print/{id}/{type?}','GeneralDOController@viewPrint')->name('do.print');
        Route::get('/general/do/print-dot/{id}','GeneralDOController@viewPrint')->name('do.print.dot');
        Route::get('/general/do-detail/{id}/','GeneralDOController@deleteDoDetail')->name('dodetail.delete');
        Route::post('/general/do/item/','GeneralDOController@check_item')->name('do.check_item');
        Route::get('/general/do/dispatch/{id}','GeneralDOController@do_dispatch')->name('do.dispatch');
        Route::post('/general/do/dispatch/','GeneralDOController@capture')->name('do.capture');
        Route::get('/general/do/redirect/{type}/{id}','GeneralDOController@redirect_page')->name('do.redirect');
        Route::post('/general/do/add-item', 'GeneralDOController@add_item')->name('do.add_item');

        //DOWNLOADER
        Route::get('/download/{hash?}', 'DownloadController@download')->name('download');

        //Request File
        Route::get('/general/request-file', 'GeneralRequestFileController@index')->name('rf.index');
        Route::post('/general/request-file/find', 'GeneralRequestFileController@find')->name('rf.find');
        Route::post('/general/request-file/request', 'GeneralRequestFileController@request')->name('rf.request');
        Route::post('/general/request-file/approve', 'GeneralRequestFileController@approve')->name('rf.approve');
        Route::get('/general/request-file/delete/{id?}', 'GeneralRequestFileController@delete')->name('rf.delete');

        //preference
        //preference
        Route::get('/dirut/preference/{id_company}','PreferenceController@index')->name('preference');
        Route::post('/dirut/preference/store','PreferenceController@savePref')->name('pref.save');
        Route::post('/dirut/preference/storeFile','PreferenceController@upload_file')->name('pref.file.save');
        Route::get('/dirut/preference/{id}/delFile/{id_company}','PreferenceController@deleteTempFile')->name('pref.file.del');
        Route::post('/dirut/preference/store/pr','PreferenceController@store_pr')->name('pref.store_pr');
        Route::post('/dirut/preference/store/ac','PreferenceController@store_ac')->name('pref.store_ac');
        Route::post('/dirut/preference/working_environment/store','PreferenceController@store_we')->name('pref.store_we');
        Route::post('/dirut/preference/working_environment/update','PreferenceController@update_we')->name('pref.update_we');
        Route::get('/dirut/preference/working_environment/delete/{id}','PreferenceController@delete_we')->name('pref.delete_we');
        Route::get('/dirut/preference/working_environment/find/{id}','PreferenceController@find_we')->name('pref.find_we');
        Route::post('/general/wo/upload-ba', 'AssetWoController@ba')->name('wo.ba');
        Route::get('/dirut/preference/br/update/{id?}','PreferenceController@br_update')->name('pref.br_update');
        Route::get('/general/wo/print/{id?}', 'AssetWoController@print')->name('wo.print');
        Route::post('/dirut/preference/payroll/thr', 'PreferenceController@thr')->name('pref.thr');
        Route::get('/general/wo/lists/{category?}', 'AssetWoController@lists')->name('wo.list');
        Route::post('/dirut/preference/accounting', 'PreferenceController@accounting_save')->name('pref.accounting.save');
        Route::post('/dirut/preference/ppe', "PreferenceController@ppe_add")->name("pref.ppe.add");
        Route::post('/dirut/preference/ppe/storage', "PreferenceController@ppe_storage")->name("pref.ppe.storage");

        //Performa Review
        Route::get('/general/performa-review', 'GeneralPerformaReviewController@index')->name('general.pr.index');
        Route::post('/general/performa-review/add', 'GeneralPerformaReviewController@add')->name('general.pr.add');
        Route::post('/general/performa-review/approve', 'GeneralPerformaReviewController@approve')->name('general.pr.approve');
        Route::post('/dirut/preference/signature/save', 'PreferenceController@signatureSave')->name('pref.sign.save');

        //Notif Setting
        Route::get('/other/notification', 'OtherNotificationController@index')->name('other.notif.index');
        Route::post('/other/notification/check-code', 'OtherNotificationController@check_code')->name('other.notif.check_code');
        Route::post('/other/notification/add', 'OtherNotificationController@add')->name('other.notif.add');
        Route::post('/other/notification/update', 'OtherNotificationController@update')->name('other.notif.update');
        Route::get('/other/notification/delete/{id?}', 'OtherNotificationController@delete')->name('other.notif.delete');

        //REPORT
        //COA
        Route::get('/report/'.$tc.'/{code}', 'CoaReportController@view')->name('report.coa.view');

        // PL
        Route::get('/report/pl', 'PLReportController@index')->name('report.pl.index');
        Route::post('report/pl/find', 'PLReportController@find')->name('report.pl.find');
        Route::post('/report/pl/list', 'PLReportController@list_project')->name('report.pl.list.project');
        Route::post('/report/pl/add', 'PLReportController@add')->name('report.pl.add');
        Route::get('/report/pl/detail/{year}', 'PLReportController@detail')->name('report.pl.detail');
        Route::get('/report/pl/actual-cost/{year}', 'PLReportController@actual')->name('report.pl.actual');
        Route::post('/report/pl/actual-cost/whitelist/{type?}', 'PLReportController@whitelists')->name('report.pl.whitelists');
        Route::get('/report/pl/actual-cost/modal/{type?}/{id?}', 'PLReportController@modal')->name('report.pl.modal');
        Route::post('/report/pl/actual/update/{code?}', 'PLReportController@update_report')->name('report.pl.update');
        Route::get('/report/pl/actual/excel/{id}','PLReportController@excel_export')->name('report.pl.excel');

        // BS
        Route::get('/report/balance-sheet', 'BSReportController@index')->name('report.bs.index');

        // DEPRECIATION
        Route::get('/finance/depreciation', 'FinanceDepreciationController@index')->name('finance.dp.index');
        Route::get('/finance/depreciation/items/list', 'FinanceDepreciationController@items_list')->name('finance.dp.items');
        Route::get('/finance/depreciation/get/{id?}', 'FinanceDepreciationController@get_data')->name('finance.dp.get');
        Route::get('/finance/depreciation/detail/{id?}', 'FinanceDepreciationController@detail')->name('finance.dp.detail');
        Route::get('/finance/depreciation/'.$tc.'/list', 'FinanceDepreciationController@tc_list')->name('finance.dp.tc');
        Route::get('/finance/depreciation/delete/{id?}', 'FinanceDepreciationController@delete')->name('finance.dp.delete');
        Route::post('/finance/depreciation/add', 'FinanceDepreciationController@add')->name('finance.dp.add');
        Route::post('/finance/depreciation/update', 'FinanceDepreciationController@update')->name('finance.dp.update');

        //MAPS
        Route::get('/general/maps', 'GeneralMaps@index')->name('general.maps.index');
        Route::post('/general/maps/marker/crew', 'GeneralMaps@markers_crew_loc')->name('general.maps.markers.crew');
        Route::post('/general/maps/marker/office', 'GeneralMaps@markers_office_loc')->name('general.maps.markers.office');
        Route::get('/general/maps/marker/employees/{type?}/{id?}', 'GeneralMaps@emp_list')->name('general.maps.employees');

        //Transaction summary
        Route::get('/finance/transaction-summary', 'FinanceTransactionSummary@index')->name('fts.index');
        Route::post('/finance/transaction-summary', 'FinanceTransactionSummary@update_data')->name('fts.update');

        Route::prefix('/report/trial-balance')->group(function(){
            Route::get("/", "ReportTrialBalance@index")->name('report.tb.index');
            Route::post("/", "ReportTrialBalance@index")->name('report.tb.index');
        });

        Route::prefix('/report/journal')->group(function(){
            Route::get("/", "ReportJournal@index")->name('report.journal.index');
            Route::post("/", "ReportJournal@index")->name('report.journal.index');
        });

        Route::prefix('/report/general-journal')->group(function(){
            Route::get("/", "ReportJournal@index_general")->name('report.journal_general.index');
            Route::post("/", "ReportJournal@index_general")->name('report.journal_general.index');
        });

        //occurrence
        Route::get('/general/berita-acara', 'GeneralOccurrenceLetter@index')->name('oletter.index');
        Route::get('/general/berita-acara/view/{type?}/{id?}', 'GeneralOccurrenceLetter@form')->name('oletter.form');
        Route::get('/general/berita-acara/detail-get/{id?}', 'GeneralOccurrenceLetter@detail_get')->name('oletter.detail_get');
        Route::get('/general/berita-acara/delete/detail/{id?}', 'GeneralOccurrenceLetter@detail_delete')->name('oletter.detail_delete');
        Route::post('/general/berita-acara/form/update', 'GeneralOccurrenceLetter@form_update')->name('oletter.form_update');
        Route::post('/general/berita-acara/add-form', 'GeneralOccurrenceLetter@add_form')->name('oletter.form.add');
        Route::get('/general/berita-acara/detail/{type?}/{id?}', 'GeneralOccurrenceLetter@detail')->name('oletter.detail');
        Route::post('/general/berita-acara/add', 'GeneralOccurrenceLetter@add')->name('oletter.add');
        Route::post('/general/berita-acara/ba-add', 'GeneralOccurrenceLetter@_add')->name('oletter._add');
        Route::get('/general/berita-acara/ba-get/{id?}', 'GeneralOccurrenceLetter@_get_ol')->name('oletter._get');
        Route::get('/general/berita-acara/ba-delete/{id?}', 'GeneralOccurrenceLetter@_delete')->name('oletter._delete');
        Route::post('/general/berita-acara/approve', 'GeneralOccurrenceLetter@approve')->name('oletter.approve');
        Route::get('/general/berita-acara/print/{id?}/{type?}', 'GeneralOccurrenceLetter@print')->name('oletter.print');

        //exchange rate
        Route::prefix('report/exchange-rate')->group(function(){
            Route::get('/insert', 'ReportExchangeRate@insert_view')->name('report.er.insert');
            Route::post('/insert', 'ReportExchangeRate@insert')->name('report.er.insert_save');
            Route::get('/',  'ReportExchangeRate@index')->name('report.er.index');
            Route::get('/get/{id?}',  'ReportExchangeRate@get')->name('report.er.get');
            Route::get('/delete/{id?}',  'ReportExchangeRate@delete')->name('report.er.delete');
            Route::get('/copy/{id?}',  'ReportExchangeRate@copy')->name('report.er.copy');
            Route::post('/add',  'ReportExchangeRate@add')->name('report.er.add');
            Route::post('/update',  'ReportExchangeRate@update')->name('report.er.update');
        });

        //covid protocol
        Route::prefix('/general/covid-protocol')->group(function(){
            Route::get('/', 'GeneralCovidProtocol@index')->name('general.covid.index');
            Route::get('/setting', 'GeneralCovidProtocol@setting')->name('general.covid.setting');
            Route::get('/add-protocol', 'GeneralCovidProtocol@add')->name('general.covid.add');
            Route::post('/store', 'GeneralCovidProtocol@store')->name('general.covid.store');
            Route::post('/update', 'GeneralCovidProtocol@update')->name('general.covid.update');
            Route::get('/view/{id?}', 'GeneralCovidProtocol@view')->name('general.covid.view');
            Route::get('/delete/{id?}', 'GeneralCovidProtocol@delete')->name('general.covid.delete');

            // emp
            Route::post('/employee/add', 'GeneralCovidProtocol@emp_add')->name('general.covid.emp_add');
            Route::get('/employee/detail/{id?}', 'GeneralCovidProtocol@emp_detail')->name('general.covid.emp_detail');
            Route::get('/employee/view/{id?}/{type?}', 'GeneralCovidProtocol@emp_detail')->name('general.covid.emp_view');
            Route::get('/employee/delete/{id?}', 'GeneralCovidProtocol@emp_delete')->name('general.covid.emp_delete');
            Route::post('/employee/update/{type?}/{id?}', 'GeneralCovidProtocol@emp_update')->name('general.covid.emp_update');
            Route::get('/employee/export/{type?}', 'GeneralCovidProtocol@emp_export')->name('general.covid.emp_export');
        });

        Route::prefix('/general/operation')->group(function () {
            Route::get('/', 'GeneralOperationReport@index')->name('general.operation.index');
            Route::get('/{type}/view/{id}', "GeneralOperationReport@setting")->name('general.operation.setting');
            Route::get('/report/add/{id}', "GeneralOperationReport@report_add")->name('general.operation.report_add');
            Route::post('/logo-setting', "GeneralOperationReport@logo_setting")->name('general.operation.logo_setting');
            Route::post('/setting/add', "GeneralOperationReport@setting_add")->name('general.operation.setting_add');
            Route::post('/setting/update', "GeneralOperationReport@setting_update")->name('general.operation.setting_update');
            Route::get('/setting/delete-record/{id}', "GeneralOperationReport@setting_delete")->name('general.operation.setting_delete');
            Route::get('/setting/get-record/{id?}', "GeneralOperationReport@setting_get")->name('general.operation.setting_get');
            Route::get('/add-form-description', 'GeneralOperationReport@add_form_desc')->name("general.operation.add_form");
            Route::get('/add-form-attachment', 'GeneralOperationReport@add_form_attachment')->name("general.operation.add_form_attachment");
            Route::post("/post-report/{id}", 'GeneralOperationReport@post_report')->name("general.operation.post.report");
            Route::post("/update-report/{id}", 'GeneralOperationReport@update_report')->name("general.operation.update.report");
            Route::get('/report/detail/{id}', 'GeneralOperationReport@report_detail')->name('general.operation.report.detail');
            Route::get('/report/delete/{id}', 'GeneralOperationReport@report_delete')->name('general.operation.report.delete');
            Route::get('/report/attach/delete/{id}', 'GeneralOperationReport@attach_delete')->name('general.operation.attach.delete');
            Route::post('/report/item/add', 'GeneralOperationReport@item_add')->name('general.operation.item.add');
            Route::post('/report/item/calculate', 'GeneralOperationReport@item_calculate')->name('general.operation.item.calculate');
            Route::post('/report/item/lock/{id}', 'GeneralOperationReport@item_lock')->name('general.operation.item.lock');
            Route::get('/templates', 'GeneralOperationReport@templates')->name('general.operation.templates');
            Route::post('/templates/add', 'GeneralOperationReport@template_add')->name('general.operation.templates.add');
            Route::get('/templates/edit/{id}', 'GeneralOperationReport@template_edit')->name('general.operation.templates.edit');
            Route::post('/templates/update/layout', "GeneralOperationReport@template_update_layout")->name("general.operation.templates.update_layout");
            Route::get('/report/print/{id}', "GeneralOperationReport@print")->name("general.operation.report.print");
        });

        Route::get('/report/account-receivable', 'ARReport@index')->name('report.ar.index');
        Route::post('/report/account-receivable', 'ARReport@index')->name('report.ar.index.post');
        Route::post('/report/account-receivable/update', 'ARReport@update')->name('report.ar.update');
        Route::get('/report/account-payable', 'ARReport@indexAP')->name('report.ap.index');
        Route::post('/report/account-payable', 'ARReport@indexAP')->name('report.ap.index.post');
        Route::post('/report/account-payable/updateAP', 'ARReport@updateAP')->name('report.ap.update');

        Route::prefix('/technical-engineering/pressure-vessel')->group(function(){
            Route::get('/', 'TePressureVessel@index')->name('te.pv.index');
            Route::get('/add-record', 'TePressureVessel@add_record')->name('te.pv.add_record');
            Route::post('/add', 'TePressureVessel@add')->name("te.pv.add");
            Route::get('/delete/{id}', 'TePressureVessel@delete')->name("te.pv.delete");
            Route::get('/view/{id}', 'TePressureVessel@view')->name('te.pv.view');
            Route::get('/duplicate/{id}', 'TePressureVessel@duplicate')->name('te.pv.duplicate');
            Route::get('/export/', 'TePressureVessel@export')->name('te.pv.export');
            Route::get('/chart/{code}', 'TePressureVessel@chart')->name('te.pv.chart');
            Route::get('/chart-data/{code}', 'TePressureVessel@chart_data')->name('te.pv.chart.data');
        });

        Route::prefix('/general/drivers')->group(function(){
            Route::get('/', 'DriverController@list_drivers')->name('general.driver.index');
            Route::post('/do/add', 'DriverController@add_do')->name('general.driver.do.add');
            Route::get('/update-status/{id?}', 'DriverController@update_status')->name('general.driver.update_status');
            Route::post('/checkout', 'DriverController@checkout')->name('general.driver.checkout');
            Route::get("/delete/{id}", "DriverController@delete")->name('general.driver.delete');
            Route::post('/do/assign', 'DriverController@assign_do')->name('general.driver.do.assign');
            Route::get('/do/remove/{id}', 'DriverController@remove_do')->name("general.driver.do.remove");
            Route::get('/do/view/{id}', 'DriverController@view_do')->name("general.driver.do.view");
        });

        Route::prefix("hrd/crew-location-notifications")->group(function(){
            Route::get("/", "HRDCrewLocationNotifications@index")->name("hrd.crewnotif.index");
            Route::post("/", "HRDCrewLocationNotifications@index")->name("hrd.crewnotif.index");
        });

        Route::prefix("hrd/crew-location-hr")->group(function(){
            Route::get("/", "HRDCrewLocation@index")->name("hrd.crewhr.index");
            Route::post("/", "HRDCrewLocation@index")->name("hrd.crewhr.index");
        });

        Route::prefix('/asset/items-assembly')->group(function(){
            Route::get('/', 'ItemsAssembly@index')->name('items.assembly.index');
            Route::get('/list/{id}', 'ItemsAssembly@list')->name('items.assembly.list');
            Route::post('/add', 'ItemsAssembly@add_assembly')->name("items.assembly.add");
            Route::post('/add-list', 'ItemsAssembly@add_list')->name("items.assembly.add_list");
            Route::post('/approve', 'ItemsAssembly@approve')->name("items.assembly.approve");
        });

        Route::prefix("hrd/contract")->group(function(){
            Route::get('/', 'HrdContract@index')->name('hrd.contract.index');
            Route::post('/', 'HrdContract@index')->name('hrd.contract.indexPost');
            Route::get('/add-template/{id?}', 'HrdContract@add_template')->name("hrd.contract.add_template");
            Route::get('/get-fields', 'HrdContract@get_fields')->name('hrd.contract.get_field');
            Route::post('/save-template', "HrdContract@save_template")->name("hrd.contract.save");
            Route::post('/generate', 'HrdContract@generate')->name("hrd.contract.generate");
            Route::get('/pdf/{id?}', 'HrdContract@pdf')->name("hrd.contract.pdf");
        });

        Route::prefix("general/meeting-zoom")->group(function(){
            Route::post('/', "GeneralMeetingZoom@store")->name("mz.store");
            Route::post("/join", "GeneralMeetingZoom@join")->name("mz.join");
            Route::get("/view/{id}", "GeneralMeetingZoom@get_detail")->name("mz.view");
        });

        Route::get('/export/{type}/{code}', [ExportTC::class, 'export'])->name('export.tc');

        Route::get('/dirut/preference/employee-variables/{id_company}', 'EmployeeVariables@employee_variables')->name('employee_variables');
        Route::post('/dirut/preference/employee-variables/add', 'EmployeeVariables@add')->name('employeevar.add');
        Route::get('/dirut/preference/employee-variables/delete/{id?}', 'EmployeeVariables@delete')->name('employeevar.delete');
        Route::post('/dirut/preference/employee-variables/edit', 'EmployeeVariables@edit')->name('employeevar.edit');

        Route::get('/phpinfo', function(){
            return phpinfo();
        });

        Route::get("item/barcode/{id}", "BarcodeGenerate@generate")->name("barcode.generate");

    });
    Route::group(['middleware' => 'guest'], function (){
        Route::get('/', [
            'uses' => 'Auth\LoginController@showLoginForm'
        ]);
    });

    Auth::routes();
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/get-company/{id?}', 'Auth\LoginController@get_company')->name('home.get_company');
    Route::group(['namespace' => 'Config'], function(){
        Route::get('/success', 'InstallWizardController@success')->name('install.success');
    });
});

Route::get('contract/{id?}', 'HrdContract@view')->name("hrd.contract.view");
Route::post('contract/approve', "HrdContract@approve")->name("hrd.contract.approve");
Route::post("ppe/do", "HrdContract@ppe_do")->name("employee.hrd.ppe_do");
Route::get("ppe/{id?}", "HrdContract@ppe_emp")->name("hrd.ppe");
Route::get('page/{type}/{id}', "HrdContract@landing_page")->name("hrd.contract.landing");

Route::group(['middleware' => 'isConfig', 'namespace' => 'Config'], function(){
    Route::get('/install', 'InstallWizardController@index')->name('install');
    Route::post('/install/submit', 'InstallWizardController@submit')->name('install.submit');
});


Route::get('/driver/form', 'DriverController@index')->name('driver.index');
Route::post('/driver/form', 'DriverController@add')->name('driver.add');
Route::get('/driver/checkout/{id?}', 'DriverController@checkout_driver')->name('driver.checkout');
Route::get('/driver/checkout-success', 'DriverController@checkout_success')->name("driver.checkout_success");
Route::post('/driver/checkout', 'DriverController@checkout_post')->name("driver.checkout_post");

Route::get("/cron-reset-daily", "HomeController@reset_daily");

Route::get('/privacy', function(){
    return view('privacy');
});




