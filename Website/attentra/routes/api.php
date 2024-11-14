<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');

Route::post('/user/apiLogin', 'Auth\API_LoginController@apiLogIn');
Route::post('/user/apiRegister', 'API_UserController@apiRegister');
Route::post('/user/apiLogout', 'Auth\API_LoginController@apilogout')->middleware('jwt.auth');
Route::post('/user/apiIndex', 'API_UserController@apiIndex')->middleware('jwt.auth');
Route::post('/user/apiUpdate', 'API_UserController@apiUpdate')->middleware('jwt.auth');
Route::post('/user/apiDelete', 'API_UserController@apiDestroy')->middleware('jwt.auth');
Route::post('/user/apiRemovePhoneCode', 'API_UserController@apiRemovePhoneCode')->middleware('jwt.authCeo');


Route::post('/company/apiIndex', 'API_CompanyController@apiIndex')->middleware('jwt.authMiddleCeo');
Route::post('/company/apiStore', 'API_CompanyController@apiStore')->middleware('jwt.authCeo');
Route::post('/company/apiUpdate', 'API_CompanyController@apiUpdate')->middleware('jwt.authCeo');
Route::post('/company/apiDelete', 'API_CompanyController@apiDestroy')->middleware('jwt.authCeo');
Route::post('/company/apiAddCompanyMember', 'API_UserController@apiStoreAddMembers')->middleware('jwt.authMiddleCeo');
Route::post('/company/apiListMembers', 'API_UserController@apiListMembers')->middleware('jwt.authMiddleCeo');
Route::post('/company/apiChangeSelfRollCall', 'API_CompanyController@apiChangeSelfRollCall')->middleware('jwt.authCeo');


Route::post('/track/apiStore', 'API_TrackController@apiStore');
Route::post('/track/apiGenerate', 'API_TrackController@apiGenerate')->middleware('jwt.auth');
Route::post('/track/apiIndex', 'API_TrackController@apiIndex')->middleware('jwt.authMiddleCeo');
Route::post('/track/apiList', 'API_TrackController@apiList')->middleware('jwt.authMiddleCeo');
Route::post('/track/apiDelete', 'API_TrackController@apiDelete')->middleware('jwt.authMiddleCeo');


Route::post('/mission/apiIndex', 'API_MissionController@apiIndex')->middleware('jwt.authMiddleCeo');
Route::post('/mission/apiStore', 'API_MissionController@apiStore')->middleware('jwt.authMiddleCeo');
Route::post('/mission/apiUpdate', 'API_MissionController@apiUpdate')->middleware('jwt.authMiddleCeo');
Route::post('/mission/apiDelete', 'API_MissionController@apiDestroy')->middleware('jwt.authMiddleCeo');
Route::post('/mission/apiListMembers', 'API_MissionController@apiListMembers')->middleware('jwt.authMiddleCeo');

Route::post('/attendance/apiStore', 'API_AttendanceController@apiStore')->middleware('jwt.authDevice');
Route::post('/attendance/apiStoreManual', 'API_AttendanceController@apiStoreManual')->middleware('jwt.authMiddleCeo');
Route::post('/attendance/apiEdit', 'API_AttendanceController@apiEdit')->middleware('jwt.authMiddleCeo');
Route::post('/attendance/apiDelete', 'API_AttendanceController@apiDelete')->middleware('jwt.authMiddleCeo');
Route::post('/attendance/apiIndex', 'API_AttendanceController@apiIndex')->middleware('jwt.auth');
Route::post('/attendance/apiStoreAutoCeo', 'API_AttendanceController@apiStoreAutoCeo')->middleware('jwt.authMiddleCeo');
Route::post('/attendance/apiStoreSelfLocation', 'API_AttendanceController@apiStoreSelfLocation')->middleware('jwt.auth');


Route::post('/companyusermodule/apiStore', 'API_CompanyUserModuleController@apiStore')->middleware('jwt.authCeo');

Route::post('/user/apiPasswordReset', 'API_ResetPasswordController@apiReset')->middleware('jwt.auth');

Route::post('/user/apiPasswordForget', 'API_ForgotPasswordController@apiForget');

Route::post('/country/apiIndex', 'API_CountryController@apiIndex');

Route::post('/payment/apiIndex', 'API_PaymentController@apiIndex')->middleware('jwt.auth');
Route::post('/payment/apiKey', 'API_PaymentController@apiKey')->middleware('jwt.auth');
Route::post('/payment/apiStore', 'API_PaymentController@apiStore')->middleware('jwt.auth');


Route::post('/module/apiUserModuleIndex', 'API_ModuleController@apiUserModuleIndex')->middleware('jwt.authCeo');
Route::post('/module/apiCompanyModuleIndex', 'API_ModuleController@apiCompanyModuleIndex')->middleware('jwt.authCeo');

Route::post('/language/apiIndex', 'API_LanguageController@apiIndex');

Route::post('/home/apiGetVersion', 'API_HomeController@apiGetVersion');
Route::post('/home/apiIndex', 'API_HomeController@apiIndex')->middleware('jwt.auth');
