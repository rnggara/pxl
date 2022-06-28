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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', 'Api\LoginController@login');
Route::get('company' , 'Api\LoginController@getCompany');

Route::get("assetgateway/refresh/{last_id}/{id_wh?}", "Api\ItemsController@get_item_gateway");
Route::get("assetgateway/refresh1/{key}", "Api\ItemsController@get_item_gateway_unapproved");

Route::get("assetgateway/do/{last_id}", "Api\DeliveryOrderController@get_last_id");
Route::post("assetgateway/do/insert", "Api\DeliveryOrderController@insert_gateway");
Route::post("assetgateway/do/receive", "Api\DeliveryOrderController@receive_gateway");

Route::group(['middleware' => 'apiauth'], function(){
    //PO
    Route::get('po/{comp_id}','Api\AssetPoController@index');
    Route::get('po/detail/{comp_id}/{id}','Api\AssetPoController@getDetail');
    Route::post('po/approve', 'Api\AssetPoController@approve');
    //PE
    Route::get('pe/{comp_id}','Api\AssetPreController@index');
    Route::get('pe/detail/{comp_id}/{id}','Api\AssetPreController@getDetail');
    Route::post('pe/approve', 'Api\AssetPreController@approve');
    //PR
    Route::get('pr/{comp_id}','Api\AssetPreController@indexPr');
    Route::get('pr/detail/{comp_id}/{id}','Api\AssetPreController@getDetailPr');
    Route::post('pr/approve', 'Api\AssetPreController@approvePr');
    //FR
    Route::get('fr/{comp_id}','Api\AssetPreController@indexFr');
    Route::get('fr/detail/{comp_id}/{id}','Api\AssetPreController@getDetailFr');
    Route::post('fr/approve', 'Api\AssetPreController@approveFr');
    //WO
    Route::get('wo/{comp_id}','Api\AssetWoController@index');
    Route::get('wo/detail/{comp_id}/{id}','Api\AssetWoController@getDetail');
    Route::post('wo/approve', 'Api\AssetWoController@approve');
    //SE
    Route::get('se/{comp_id}','Api\AssetSreController@index');
    Route::get('se/detail/{comp_id}/{id}','Api\AssetSreController@getDetail');
    Route::post('se/approve', 'Api\AssetSreController@approve');
    //SR
    Route::get('sr/{comp_id}','Api\AssetSreController@indexSR');
    Route::get('sr/detail/{comp_id}/{id}','Api\AssetSreController@getDetailSR');
    Route::post('sr/approve', 'Api\AssetSreController@approveSR');
    //SO
    Route::get('so/{comp_id}','Api\AssetSreController@indexSO');
    Route::get('so/detail/{comp_id}/{id}','Api\AssetSreController@getDetailSO');
    Route::post('so/approve', 'Api\AssetSreController@approveSO');
    //TO
    Route::get('to/{comp_id}','Api\GeneralTravelOrderController@index');
    Route::get('to/get/{id}','Api\GeneralTravelOrderController@get');
    Route::post('to/approve', 'Api\GeneralTravelOrderController@approve');

    //user activity
    Route::get('users/{comp_id}','Api\LoginController@getUser');
    Route::get('users/activity/{comp_id}/{user_id}','Api\LoginController@getUserActivty');
    Route::post('users/add_activity','Api\LoginController@addActivity');

    //DO
    Route::get('do/{comp_id}','Api\DeliveryOrderController@index');
    Route::get('do/waiting/{comp_id}','Api\DeliveryOrderController@indexDoWaiting');
    Route::get('do/delivered/{comp_id}','Api\DeliveryOrderController@indexDoDelivered');
    Route::get('do/detail/{comp_id}/{id}','Api\DeliveryOrderController@getDetail');
    Route::post('do/approve', 'Api\DeliveryOrderController@approveDO');
    Route::post('do/receive', 'Api\DeliveryOrderController@approveDO');

    Route::get('notif/{comp_id}/{user_id}','Api\LoginController@getnotif');


    // MAP
    Route::prefix('map')->group(function () {
        Route::get('/', 'Api\MapController@index');
        Route::get('/{type}/{id}', 'Api\MapController@view');
    });

    Route::prefix('items')->group(function() {
        Route::get('/{id}', 'Api\ItemsController@get_items_approval');
        Route::get('/detail/{id}', 'Api\ItemsController@detail');
        Route::get('/category/{compid}', 'Api\ItemsController@get_category');
        Route::get('/classification/{id_cat}', 'Api\ItemsController@get_class');
        Route::post('/get-item-code', 'Api\ItemsController@get_item_code');
    });

    //DO depart
    Route::get('do/get-detail/{id}', 'Api\DeliveryOrderController@get_detail');
    Route::post('do/dispatch', 'Api\DeliveryOrderController@dispatch_do');
    Route::get('do/generate/qr', 'Api\DeliveryOrderController@qrMobileGenerate');
});


