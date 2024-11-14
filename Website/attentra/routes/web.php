<?php
use Illuminate\Support\Facades\Input;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


//Route::group(['middleware' => 'web'], function () {
//    Route::get('/', function ()    {
//        // Uses Auth Middleware
//    });
//
//    Route::get('user/profile', function () {
//        // Uses Auth Middleware
//    });
//});
//->middleware('auth:web');



Route::get('/getAPK', [ 'uses' => 'HomeController@getAPK']);
Route::get('/', 'HomeController@index');
//Route::get('/test', function()
//{
//    return view('layouts/qrtest');
//});
Route::get('/test', function()
{
//    return view('t/testCalender');
    return view('test');
//    return view('t/testCurrentPossition');
    return view('track/testMultipleMarkers');
    return view('t/testRouteBetween2Markers');
    return view('t/testTrackFind');
    return view('t/testTrackMerge');

});


Route::get('/articles', 'NewsController@index');
Route::get('/articles/{article_Page_Number}', 'NewsController@index');
route::get('/articles/show/{news_id}/{news_guid}',['as'=>'news.show','uses'=>'NewsController@show']);
//Route::get('/test','CompanyController@countOfAttendance');

Route::get('/aboutus', 'AboutUsController@index');
Route::get('/license', function(){
    return view('aboutus/license');
});
Route::get('/questions', function(){
    return view('aboutus/questions');
});

///////////////////////////////////////////////////////////News Related////////////////////////////////
route::get('/news/show/{news_id}/{news_guid}',['as'=>'news.show','uses'=>'NewsController@show']);
route::get('/news',['as'=>'news.index','uses'=>'NewsController@index']);
Route::get('/news/image/{filename}', [
    'as'   => 'news.image',
    'uses' => 'NewsController@getImage',
]);
/// ///////////////////////////////////////////////////////////////////////////////////////////////////

Route::post('/forgetpassword','ForgetPasswordController@forget_password');


///////////////////////language related routs/////////////////
///////////////////////language related web routs/////////////
///////////////////////Get////////////////////////////////////
Route::get('lang/{lang}', ['as'=>'lang.switch', 'uses'=>'LanguageController@switchLang']);

////////////////////////user related routs/////////////////////
////////////////////////user related web routs/////////////////
////////////////////////Get////////////////////////////////////
Route::get('/user', 'UserController@index')->middleware('auth.basic');
Route::get('/user/list', 'UserController@userList');
Route::get('/user/edit/{user_id}/{user_guid}', 'UserController@edit');
Route::get('user/destroy/{user_id}/{user_guid}', 'UserController@destroy');
Route::get('/user/show/{user_id}/{user_guid}', 'UserController@show');
Route::get('/user/create', 'UserController@create');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
//Route::get('/user/login', 'LoginController@login');
//Route::get('/user/create', 'UserController@create')->middleware('auth:web');
///////////////////////////////////////////////////////////////
////////////////////////Post///////////////////////////////////
Route::post('/user/store', 'UserController@store');
Route::post('/user/login', 'Auth\LoginController@login');
Route::post('/user/update',['as' => 'user.update', 'uses' => 'UserController@update']);
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////for user related api routes/////////
////////////////////////////Get/////////////////////////////////
Route::get('/api/user', 'UserController@indexApi');


////////////////////////////////Home related routes////////////////////
///////////////////////////////Get/////////////////////////////////////
Route::get('/error', 'HomeController@error');


////////////////////////////////////////////////////////Feedback related ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////Feedback related ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////Feedback related ///////////////////////////////////////////////////////
//////////////////////////Feedback web realated ///////////////////////
//////////////////////////Get//////////////////////////////////////////
Route::get('/feedback/create', 'FeedbackController@create');
Route::get('/feedback','FeedbackController@index');
Route::get('/feedback/show/{feedback_id}/{feedback_guid}','FeedbackController@show');
Route::get('/feedback/delete/{feedback_id}/{feedback_guid}','FeedbackController@destroy');
Route::post('/feedback/store' , ['as' => 'feedback.store' , 'uses' => 'FeedbackController@store']);
Route::post('/order/store' , ['as' => 'feedback.store' , 'uses' => 'FeedbackController@storeOrder']);
////////////////////////////////////////////////////////end of Feedback related ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////end of Feedback related ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////end of Feedback related ///////////////////////////////////////////////////////


Auth::routes();

Route::get('/home', 'HomeController@index');
// Authentication Routes...
Route::auth();

////////////////////////////////////////////////////////download or upload related ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////download or upload related ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////download or upload related ///////////////////////////////////////////////////////
//////////////////////////download or upload  web realated ///////////////////////
//////////////////////////Get//////////////////////////////////////////
Route::group(['middleware' => ['auth.admin']], function () {

    route::get('/uploadCreate',['as'=>'upload.create','uses'=>'DownloadController@create']);
    route::get('upload/uploadlList',['as'=>'upload.store','uses'=>'DownloadController@uploadlList']);
    Route::get('upload/delete/{feedback_id}/{feedback_guid}','DownloadController@destroy');
//shoing download list in webpage list
    Route::get('downloadsList','DownloadController@index');
///////////////////////////////////////////////////////////////
////////////////////////Post///////////////////////////////////



//route::get('company/ListOfMembers',['as'=>'company.ListOfMembers','uses'=>'UserController@ListMembers']);
///editMission/{{$Mission->mission_id}}/{{$Mission->mission_guid}}'
///deleteMission/{{$Mission->mission_id}}/{{$Mission->mission_guid}}





////////////////////////////////////////////////////////language profile related///////////////////////////////////////////////////////
////////////////////////////////////////////////////////language profile related///////////////////////////////////////////////////////
////////////////////////////////////////////////////////language profile related///////////////////////////////////////////////////////
    route::get('/language/create',['as'=>'language.crate','uses'=>'LanguageController@create']);
    route::get('/language/index',['as'=>'language.index','uses'=>'LanguageController@index']);
    route::post('/language/store',['as'=>'language.index','uses'=>'LanguageController@store']);

////////////////////////////////////////////////////////module profile related///////////////////////////////////////////////////////
////////////////////////////////////////////////////////module profile related///////////////////////////////////////////////////////
////////////////////////////////////////////////////////module profile related///////////////////////////////////////////////////////
    route::get('/module/create',['as'=>'module.create','uses'=>'ModuleController@create']);
    route::get('/module/index',['as'=>'module.index','uses'=>'ModuleController@index']);
    route::post('/module/store',['as'=>'module.index','uses'=>'ModuleController@store']);
    route::get('/module/moduleEdit/{module_id}/{module_guid}',['as'=>'module.edit','uses'=>'ModuleController@edit']);
    route::post('/module/update',['as'=>'module.update','uses'=>'ModuleController@update']);

});




//******************************************************** middleware permissible for ceo or admin****************************************************
//******************************************************** middleware permissible for ceo or admin****************************************************
//******************************************************** middleware permissible for ceo or admin****************************************************
Route::group(['middleware' => ['auth.ceo']], function (){
    ////////////////////////////////////////////////////////company related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////company related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////company related ///////////////////////////////////////////////////////
    route::get('company/create',['uses'=>'CompanyController@create']);
    route::post('company/store',['as'=>'company.store','uses'=>'CompanyController@store']);
    route::get('company/remove/{company_id}/{company_guid}',['as'=>'company.remove','uses'=>'CompanyController@destroy']);
    route::get('companyEdit/{company_id}/{company_guid}',['as'=>'company.edit','uses'=>'CompanyController@edit']);
    route::post('company/update',['as'=>'company.update','uses'=>'CompanyController@update']);

    //payment
    route::get('payment/index',['as'=>'payment.index','uses'=>'PaymentController@index']);
    route::post('payment/store',['as'=>'payment.store','uses'=>'PaymentController@store']);
    ////////////////////////////////////////////////////////////////////////////////
    route::get('module/purchases',['as'=>'module.purchases','uses'=>'CompanyUserModuleController@purchasesList']);

});

Route::get('/payment/verification',['as'=>'payment.verification','uses'=>'PaymentController@verification']);


//******************************************************** middleware permissible for MiddleCEO or ceo or admin****************************************************
//******************************************************** middleware permissible for MiddleCEO or ceo or admin****************************************************
//******************************************************** middleware permissible for MiddleCEO or ceo or admin****************************************************
Route::group(['middleware' => ['auth.MiddleCEO']], function (){
    ////////////////////////////////////////////////////////company related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////company related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////company related ///////////////////////////////////////////////////////
    route::get('company/AddMembers/{company_id}/{company_guid}',['as'=>'user.addMembers','uses'=>'UserController@AddMembers']);
    route::post('company/storeAddMembers',['as'=>'company.storeAddMembers','uses'=>'UserController@storeAddMembers']);

    ////////////////////////////////////////////////////////user of company related///////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////user of company related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////user of company related ///////////////////////////////////////////////////////
    route::get('/userOfCompanyEdit/{user_id}/{user_guid}',['as'   => 'company.userEdit','uses'=>'UserController@UserOfCompanyEdit']);
    route::get('/userOfCompanyDestroy/{user_id}/{user_guid}',['as'=>'company.remove','uses'=>'UserController@destroy']);
    Route::post('/user/updateForEmployer',['as' => 'user.updateForEmployer', 'uses' => 'UserController@updateForEmployer']);
    route::get('/ReportList/{company_id}/{company_guid}/{user_id}/{user_guid}',['uses'=>'AttendanceController@ReportList']);

    ////////////////////////////////////////////////////////attendance related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////attendance related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////attendance related ///////////////////////////////////////////////////////
    route::get('/attendance/create/{company_id}/{company_guid}',['as'=>'attendance.create','uses'=>'AttendanceController@create']);
    route::post('/attendance/store',['as'=>'attendance.store','uses'=>'AttendanceController@store']);
    route::get('/editAttendance/{attendance_id}/{attendance_guid}/{company_id}/{company_guid}',['as'=>'editAttendance','uses'=>'AttendanceController@edit']);
    route::post('/attendance/update',['as'=>'attendance.update','uses'=>'AttendanceController@update']);
    route::get('/deleteAttendance/{attendance_id}/{attendance_guid}',['as'=>'attendance.delete','uses'=>'AttendanceController@delete']);

    /////////////////////////////////////////////////////////mission related////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////mission related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////mission related ///////////////////////////////////////////////////////
    route::get('/addMission/{company_id}/{company_guid}',['as'=>'mission.addMission','uses'=>'MissionController@addMission']);
    route::post('/mission/store',['as'=>'mission.store','uses'=>'MissionController@store']);
    route::get('/editMission/{mission_id}/{mission_guid}',['as'=>'editMission','uses'=>'MissionController@edit']);
    route::post('/mission/update/',['as'=>'mission.update','uses'=>'MissionController@update']);
    route::get('/deleteMission/{mission_id}/{mission_guid}',['as'=>'Mission.delete','uses'=>'MissionController@delete']);

    ////////////////////////////////////////////////////////module profile related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////module profile related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////module profile related///////////////////////////////////////////////////////
    route::get('/module/index/{company_id}/{company_guid}',['as'=>'module.index','uses'=>'ModuleController@index']);
    route::get('/module/publicindex/{company_id}/{company_guid}',['as'=>'module.publicindex','uses'=>'ModuleController@publicindex']);
    route::post('/activeModule',['as'=>'module.activeModule','uses'=>'CompanyUserModuleController@activeModule']);
});

route::get('/index',['as'=>'company.setProfile','uses'=>'CompanyController@setProfile']);

//******************************************************** middleware permissible for Employee or MiddleCEO or ceo or admin ****************************************************
//******************************************************** middleware permissible for Employee or MiddleCEO or ceo or admin ****************************************************
//******************************************************** middleware permissible for Employee or MiddleCEO or ceo or admin ****************************************************
Route::group(['middleware' => ['auth.Employee']], function (){

    ///////////////////////////////////////tracking/////////////////////////////////////////////
    route::get('/tracking/{user_id}/{user_guid}',['as'   => 'track.list','uses'=>'TrackController@Index']);
    route::get('/map/{user_id}/{user_guid}/{tarck_group}',['uses'=>'TrackController@showMap']);
    route::get('/deleteTrack/{track_group}',['as'=>'track.delete','uses'=>'TrackController@delete']);

    ///////////////////////////////////////redirect after login///////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////company related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////company related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////company related ///////////////////////////////////////////////////////
    route::get('companyList',['as'=>'company.list','uses'=>'CompanyController@index']);
    route::get('company/ListMembers/{company_id}/{company_guid}',['as'=>'company.ListMembers','uses'=>'UserController@ListMembers']);
    route::get('company/recentlyMissionList/{company_id}/{company_guid}',['as'=>'company.missionList','uses'=>'MissionController@index']);
    Route::get('/company/{filename}', ['as'   => 'company.image', 'uses' => 'CompanyController@getImage']);
    Route::get('/avatars/{filename}', ['as'   => 'avatars.image', 'uses' => 'CompanyController@getAvatar']);
    Route::get('/icons/{filename}', ['as'   => 'icons.images', 'uses' => 'TrackController@geticons']);


    ////////////////////////////////////////////////////////attendance related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////attendance related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////attendance related ///////////////////////////////////////////////////////
    route::get('/attendaceList/{company_id}/{company_guid}',['as'=>'attendance.list','uses'=>'AttendanceController@index']);
    route::post('/attendance/indexReports',['as'=>'attendance.indexReports','uses'=>'AttendanceController@indexreprtAttendanceHourByValue']);


    route::get('/attendance/chart',['as'=>'attendance.chart','uses'=>'AttendanceController@chartshow']);


    route::get('/reports/{company_id}/{company_guid}',['as'=>'report.list','uses'=>'AttendanceController@reprtAttendanceHour']);

    route::post('/attendance/reports',['as'=>'attendance.reports','uses'=>'AttendanceController@reprtAttendanceHourByValue']);
    ////////////////////////////////////////////////////////QR Code related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////QR Code related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////QR Code related ///////////////////////////////////////////////////////
    Route::get('/qrcode/{user_id}/{user_guid}', 'UserController@QR_Code');

    ////////////////////////////////////////////////////////mission related////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////mission related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////mission related ///////////////////////////////////////////////////////
    route::get('/mission/userListForThisMission/{mission_id}/{mission_guid}',['as'=>'Mission.userListForThisMission','uses'=>'MissionController@userListForThisMission']);
    route::get('/userListForThisMission/{mission_id}/{mission_guid}',['as'=>'userListForThisMission.list','uses'=>'MissionController@userListForThisMission']);


    ////////////////////////////////////////////////////////employer profile related///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////employer profile related ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////employer profile related///////////////////////////////////////////////////////
    route::get('/missionList/{company_id}/{company_guid}/{user_id}/{user_guid}',['as'=>'Mission.index','uses'=>'MissionController@index']);
    route::get('/AttendanceList/{company_id}/{company_guid}/{user_id}/{user_guid}',['as'=>'Mission.index','uses'=>'AttendanceController@indexOfThisUser']);
    route::post('/AttendanceList/WorkHourThisUser',['as'=>'Mission.index','uses'=>'AttendanceController@WorkHourThisUser']);
    route::get('/attendance/lastMonth',['as'=>'attendance.lastMonth','uses'=>'AttendanceController@lastMonthChartShow']);



});

