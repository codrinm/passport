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

Route::middleware('auth:api', 'scopes:read-users,create-users,delete-users,secret-scope')->get('/user', function (Request $request) {
    //return 'working';
    //dd($request->user()->tokenCan('create-usersa'));
    return $request->user();
});
