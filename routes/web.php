<?php

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

Route::get('/', 'Auth\LoginController@showLogin');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'web'], function() {
    /** ============= Authentication ============= */
    Route::get('/auth/login', 'Auth\LoginController@showLogin');
    Route::post('/auth/signin', 'Auth\LoginController@doLogin');
    Route::get('/auth/logout', 'Auth\LoginController@doLogout');
    Route::get('/auth/register', 'Auth\RegisterController@register');
    Route::post('/auth/signup', 'Auth\RegisterController@create');
    Route::get('/auth/checking', 'Auth\LoginController@getChecking');
    Route::post('/auth/checking', 'Auth\LoginController@checking');
});

Route::group(['middleware' => ['web','auth']], function () {
    /** Dashboard */
    Route::get('dashboard/head/{date}', 'DashboardController@getHeadData');
    Route::get('dashboard/depart/{date}', 'DashboardController@getDepartData');
    Route::get('dashboard/stat/{year}', 'DashboardController@getStatYear');

    /** บุคลากร */
    Route::get('persons/list', 'PersonController@index');
    Route::get('persons/search', 'PersonController@search');
    Route::get('persons/departs', 'PersonController@departs');
    Route::get('persons/departs/head', 'PersonController@getHeadOfDeparts');
    Route::get('persons/detail/{id}', 'PersonController@detail');
    Route::get('persons/edit/{id}', 'PersonController@edit');
    Route::post('persons/update/{id}', 'PersonController@update');
    Route::post('persons/delete/{id}', 'PersonController@delete');

    /** วันหยุดราชการ */
    Route::get('holidays', 'HolidayController@getHolidays');
    Route::get('holidays/list', 'HolidayController@index');
    
    /** ประวัติ */
    Route::get('histories/profile/{id}', 'PersonController@getProfile');
    Route::get('histories/summary/{id}', 'HistoryController@summary');
    Route::get('histories/stat/{id}/{year}', 'HistoryController@getSummary');
    Route::get('histories/{id}/{year}/person', 'HistoryController@getHistoriesByPerson');

    /** การลา */
    Route::post('leaves/validate', 'LeaveController@formValidate');
    Route::get('leaves/list', 'LeaveController@index');
    Route::get('leaves/search/{year}/{type}/{status}/{menu}', 'LeaveController@search');
    Route::get('leaves/get-ajax-all', 'LeaveController@getAll');
    Route::get('leaves/get-ajax-byid/{id}', 'LeaveController@getById');
    Route::get('leaves/detail/{id}', 'LeaveController@detail');
    Route::get('leaves/add', 'LeaveController@add');
    Route::post('leaves/store', 'LeaveController@store');
    Route::get('leaves/edit/{id}', 'LeaveController@edit');
    Route::post('leaves/update', 'LeaveController@update');
    Route::post('leaves/delete/{id}', 'LeaveController@delete');
    Route::get('leaves/print/{id}', 'LeaveController@printLeaveForm');

    /** การอนุมัติ */
    Route::get('approvals/comment', 'ApprovalController@getComment');
    Route::post('approvals/comment', 'ApprovalController@doComment');
    Route::get('approvals/receive', 'ApprovalController@getReceive');
    Route::post('approvals/receive', 'ApprovalController@doReceive');
    Route::get('approvals/approve', 'ApprovalController@getApprove');
    Route::post('approvals/approve', 'ApprovalController@doApprove');
    Route::post('approvals/status', 'ApprovalController@setStatus');

    /** ยกเลิกการลา */
    Route::post('cancellations/validate', 'CancellationController@formValidate');
    Route::get('cancellations/cancel', 'CancellationController@getCancel');
    Route::post('cancellations/store', 'CancellationController@store');
    Route::get('cancellations/edit/{id}', 'CancellationController@edit');
    Route::post('cancellations/update', 'CancellationController@update');
    Route::post('cancellations/delete/{id}', 'CancellationController@delete');
    Route::get('cancellations/{personId}/person', 'CancellationController@getByPerson');
    Route::post('cancellations/approve', 'CancellationController@doApprove');
    Route::post('cancellations/comment', 'CancellationController@doComment');
    Route::post('cancellations/receive', 'CancellationController@doReceive');
    Route::get('cancellations/print/{id}', 'CancellationController@printCancelForm');

    /** บริหารบุคลากร */
    Route::get('managements/leaves', 'ManagementController@leaves');
    Route::get('managements/vacations', 'ManagementController@getVacations');

    /** รายงาน */
    Route::get('reports/daily', 'ReportController@daily');
    Route::get('reports/daily-data', 'ReportController@getDailyData');
    Route::get('reports/monthly', 'ReportController@monthly');
    Route::get('reports/monthly-data', 'ReportController@getMonthlyData');
    Route::get('reports/summary', 'ReportController@summary');
    Route::get('reports/summary-data', 'ReportController@getSummaryData');
    Route::get('reports/remain', 'ReportController@remain');
    Route::get('reports/remain-data', 'ReportController@getRemainData');
    Route::get('reports/debt-creditor/rpt/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorRpt');
    Route::get('reports/debt-creditor-excel/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorExcel');     
    Route::get('reports/debt-debttype/list', 'ReportController@debtDebttype');    
    Route::get('reports/debt-debttype/rpt/{debtType}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeRpt');
    Route::get('reports/debt-debttype-excel/{debttype}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeExcel');   
});
