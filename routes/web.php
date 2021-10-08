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
});

Route::group(['middleware' => ['web','auth']], function () {
    /** บุคลากร */
    Route::get('persons/list', 'PersonController@index');
    Route::get('persons/search/{depart}/{searchKey}', 'PersonController@search');
    Route::get('persons/{id}', 'PersonController@getById');

    /** ประวัติการลา */
    Route::get('/histories/{id}', 'HistoryController@summary');

    /** การลา */
    Route::post('leaves/validate', 'LeaveController@formValidate');
    Route::get('leaves/list', 'LeaveController@index');
    Route::get('leaves/search/{parcelId}/{status}/{searchKey}', 'LeaveController@search');
    Route::get('leaves/get-ajax-all', 'LeaveController@getAll');
    Route::get('leaves/get-ajax-byid/{id}', 'LeaveController@getById');
    Route::get('leaves/add', 'LeaveController@add');
    Route::post('leaves/store', 'LeaveController@store');
    Route::get('leaves/edit/{assetId}', 'LeaveController@edit');
    Route::put('leaves/update', 'LeaveController@update');
    Route::delete('leaves/delete/{assetId}', 'LeaveController@delete');
    Route::get('leaves/discharge', 'LeaveController@discharge');
    Route::post('leaves/discharge', 'LeaveController@doDischarge');

    /** Asset Type */
    Route::post('/asset-type/validate', 'AssetTypeController@formValidate');
    Route::get('asset-type/list', 'AssetTypeController@index');
	Route::get('asset-type/search/{cateId}/{searchKey}', 'AssetTypeController@search');
    Route::get('asset-type/get-ajax-all', 'AssetTypeController@getAll');
    Route::get('asset-type/get-ajax-byid/{typeId}', 'AssetTypeController@getById');
    Route::get('asset-type/get-ajax-bycate/{cateId}', 'AssetTypeController@getByCate');
    Route::get('asset-type/get-ajax-no/{cateId}', 'AssetTypeController@getNo');
    Route::get('asset-type/add', 'AssetTypeController@add');
    Route::post('asset-type/store', 'AssetTypeController@store');
    Route::get('asset-type/edit/{typeId}', 'AssetTypeController@edit');
    Route::put('asset-type/update', 'AssetTypeController@update');
    Route::delete('asset-type/delete/{typeId}', 'AssetTypeController@delete');

    /** Supplier */
    Route::get('supplier/list', 'SupplierController@index');
    Route::get('supplier/search/{searchKey}', 'SupplierController@search');
    Route::get('supplier/get-supplier/{creditorId}', 'SupplierController@getById');
    Route::get('supplier/add', 'SupplierController@add');
    Route::post('supplier/store', 'SupplierController@store');
    Route::get('supplier/edit/{creditorId}', 'SupplierController@edit');
    Route::put('supplier/update', 'SupplierController@update');
    Route::delete('supplier/delete/{creditorId}', 'SupplierController@delete');

    /** Report */
    Route::get('reports/summary', 'ReportController@summary');
    Route::get('reports/debt-creditor/rpt/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorRpt');
    Route::get('reports/debt-creditor-excel/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorExcel');     
    Route::get('reports/debt-debttype/list', 'ReportController@debtDebttype');    
    Route::get('reports/debt-debttype/rpt/{debtType}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeRpt');
    Route::get('reports/debt-debttype-excel/{debttype}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeExcel');
    Route::get('reports/debt-chart/{creditorId}', 'ReportController@debtChart');     
    Route::get('reports/sum-month-chart/{month}', 'ReportController@sumMonth');     
    Route::get('reports/sum-year-chart/{month}', 'ReportController@sumYear');     
});
