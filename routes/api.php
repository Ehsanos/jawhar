<?php

use Illuminate\Http\Request;

// Route::get('{any}', function() {
//   return response()->json(['status' => false, 'code' => 200, 'message' => 'عذرا النظام في وضع الصيانة يرجى المحاولة لاحقا']);
// // return view('web/suspend');
// })->where('any', '.*');
//
// Route::post('{any}', function() {
//   return response()->json(['status' => false, 'code' => 200, 'message' => 'عذرا النظام في وضع الصيانة يرجى المحاولة لاحقا']);
// // return view('web/suspend');
// })->where('any', '.*');

Route::group(['middleware' => 'auth:api'], function () {


    Route::post('/editProfile', 'API\UserController@editProfile');
    Route::post('/checkPayment', 'API\UserController@checkPayment');
    Route::post('/checkPaymentNew', 'API\UserController@checkPaymentNew');
    Route::post('/changePassword', 'API\UserController@changePassword');
    Route::post('/addPassword', 'API\UserController@addPassword');
    Route::post('/check_password', 'API\UserController@check_password');
    Route::post('/chat', 'API\UserController@chat');
    Route::get('/logout', 'API\UserController@logout');
    Route::post('/checkPromo','API\CartController@checkPromo');
    Route::post('/checkOut','API\CartController@checkOut');



    Route::get('/getOrderDetail/{id}','API\UserController@getOrderDetail');
    Route::get('/getMyOrdersByStatus/{id}','API\UserController@getMyOrdersByStatus');

    Route::get('/getPointsWalletExpections', 'API\UserController@getPointsWalletExpections');
    Route::get('/getgetNotifications', 'API\UserController@getNotifications');
    Route::get('/allNotifications', 'API\UserController@allNotifications');
    Route::get('/getHistory', 'API\UserController@getHistory');

    Route::get('myNotifications', 'API\UserController@notifications');

    Route::get('myPromoCodes', 'API\UserController@myPromoCodes');
    Route::post('addNewPromoCode', 'API\UserController@addNewPromoCode');
    Route::get('deletePromoCode/{id}', 'API\UserController@deletePromoCode');



});

//
Route::get('getSections', 'API\ExternalController@getSections');
Route::get('getServiceCountries/{id}', 'API\ExternalController@getServiceCountries');
Route::get('getLiveNumber/{section}/{service}', 'API\ExternalController@getLiveNumber');

Route::post('saveCode', 'API\UserController@saveCode');
Route::get('getUserCount', 'API\UserController@getUserCount');
//    AppController
Route::get('distributionPoints', 'API\AppController@distributionPoints');
Route::post('subPublicServiceRequest', 'API\AppController@subPublicServiceRequest');
Route::get('getGames', 'API\AppController@getGames');
Route::get('getGamesServies', 'API\AppController@getGamesServies');
Route::post('gameRequest', 'API\AppController@gameRequest');
Route::post('gameRequestTest', 'API\AppController@gameRequestTest');
Route::get('getInstitute', 'API\AppController@getInstitute');
Route::get('getInstituteCourses', 'API\AppController@getInstituteCourses');
Route::post('instituteRequest', 'API\AppController@instituteRequest');

Route::get('getPublicServices', 'API\AppController@getPublicServices');
Route::get('getSubPublicServices/{id}', 'API\AppController@getSubPublicServices');
Route::get('getNews', 'API\AppController@getNews');
Route::get('getAllMobileCompany', 'API\AppController@getAllMobileCompany');
Route::get('getMobileNetworkPackagesByCompanyID/{id}', 'API\AppController@getMobileNetworkPackagesByCompanyID');
Route::post('requestMobileNumber', 'API\AppController@requestMobileNumber');
Route::get('getAds', 'API\AppController@getAds');
Route::get('getSliders', 'API\AppController@getSliders');
Route::get('getAzkar', 'API\AppController@getAzkar');
Route::get('getAzkarDetails/{id}', 'API\AppController@getAzkarDetails');
Route::get('getSetting', 'API\AppController@getSetting');
Route::get('getAshabGames', 'API\AppController@getAshabGames');
Route::get('getAshabGameCards/{id}', 'API\AppController@getAshabGameCards');
Route::get('getNagma/{id}', 'API\AppController@getNagma');
Route::post('checkAshabBalance', 'API\AppController@checkAshabBalance');
Route::post('doAshabResultOrder', 'API\AppController@doAshabResultOrder');
Route::get('getCities', 'API\AppController@getCities');
Route::get('getOffers','API\AppController@getOffers');
Route::get('getProductsByCategoryId/{id}', 'API\AppController@getProductsByCategoryId');
Route::get('allQuestions', 'API\AppController@allQuestions');
Route::get('pages/{id}', 'API\AppController@pages');
Route::post('contactUs', 'API\AppController@contactUs');
Route::post('search', 'API\AppController@search');
Route::get('getCategory', 'API\AppController@getCategory');
Route::get('getProduct', 'API\AppController@getProduct');
Route::post('addAzkar', 'API\AppController@addAzkar');
Route::post('networkRequest', 'API\AppController@networkRequest');
Route::post('enableNotificationForNetwork', 'API\AppController@enableNotificationForNetwork');
Route::post('enableNotificationForStore', 'API\AppController@enableNotificationForStore');
Route::post('enableNotificationForInstitute', 'API\AppController@enableNotificationForInstitute');
Route::get('getRecharge', 'API\AppController@getRecharge');
Route::get('getPackages/{mobile_company_id}', 'API\AppController@getPackages');
Route::get('getPrices/{package_id}', 'API\AppController@getPrices');


//    ServiceController
Route::get('getServices', 'API\ServicesController@getServices');
Route::get('getServicesProducts', 'API\ServicesController@getServicesProducts');
Route::get('getCategories', 'API\ServicesController@getCategories');
Route::get('getSubCategories', 'API\ServicesController@getSubCategories');
Route::get('getProducts', 'API\ServicesController@getProducts');
Route::get('getProduct', 'API\ServicesController@getProduct');
Route::get('getWifi', 'API\ServicesController@getWifi');
Route::get('getNetwork', 'API\ServicesController@getNetwork');
Route::get('getNetworksCards', 'API\ServicesController@getNetworksCards');
Route::get('reNewNetworksCards', 'API\ServicesController@reNewNetworksCards');
Route::get('getNewest', 'API\ServicesController@getNewest');
Route::get('allNewest', 'API\ServicesController@allNewest');
Route::get('allMostSelling', 'API\ServicesController@allMostSelling');
Route::post('productReview', 'API\ServicesController@productReview');
Route::post('search', 'API\ServicesController@search');
Route::post('filter', 'API\ServicesController@filter');
Route::get('getServiceCards', 'API\ServicesController@getServiceCards');
Route::get('getProductReview', 'API\ServicesController@getProductReview');
Route::get('checkWhatsApp', 'API\ServicesController@checkWhatsApp');
Route::get('getCurrency', 'API\UserController@getCurrency');
Route::post('changeCurrency', 'API\UserController@changeCurrency');



//    UserController
Route::post('/login', 'API\UserController@login');
Route::post('/signUp', 'API\UserController@signUp');
Route::post('/socialSignUp', 'API\UserController@socialSignUp');
Route::post('/forgotPassword','API\UserController@forgotPassword');
Route::post('paymentRequest', 'API\UserController@paymentRequest');
Route::get('notifications', 'API\UserController@notifications');
Route::get('deletNotification', 'API\UserController@deletNotification');
Route::get('wallet', 'API\UserController@wallet');
Route::get('myOrders', 'API\UserController@myOrders');
Route::get('myAllOrders', 'API\UserController@myAllOrders');
Route::post('addBalance', 'API\UserController@addBalance');
Route::post('requestMobileBalance', 'API\UserController@requestMobileBalance');
Route::get('orderDetails', 'API\UserController@orderDetails');

//    StoreController

Route::post('/register', 'API\StoreController@register');
Route::post('/editStoreProfile', 'API\StoreController@editStoreProfile');
Route::get('/getStores', 'API\StoreController@getStores');
Route::get('/getStoreById', 'API\StoreController@getStoreById');
Route::get('/getStoreByProductId', 'API\StoreController@getStoreByProductId');
Route::get('/getStoreByWifiId', 'API\StoreController@getStoreByWifiId');
Route::get('/myProducts', 'API\StoreController@myProducts');
Route::get('/myCategory', 'API\StoreController@myCategory');
Route::get('/getCategoriesByStoreId', 'API\StoreController@getCategoriesByStoreId');
Route::get('/myNews', 'API\StoreController@myNews');
Route::get('/mySliders', 'API\StoreController@mySliders');
Route::post('/addProduct', 'API\StoreController@addProduct');
Route::post('/editProduct', 'API\StoreController@editProduct');
Route::post('/addNews', 'API\StoreController@addNews');
Route::post('/addSlider', 'API\StoreController@addSlider');
Route::post('/editSlider', 'API\StoreController@editSlider');
Route::post('/addCategory', 'API\StoreController@addCategory');
Route::post('/editCategory', 'API\StoreController@editCategory');
Route::post('/addSubCategory', 'API\StoreController@addSubCategory');
Route::post('/editSubCategory', 'API\StoreController@editSubCategory');
Route::post('/editNews', 'API\StoreController@editNews');
Route::get('/storeOrders', 'API\StoreController@storeOrders');
Route::post('/changeOrderStatus', 'API\StoreController@changeOrderStatus');
Route::get('getStoreSubCategories', 'API\StoreController@getStoreSubCategories');
Route::post('likeStore', 'API\StoreController@likeStore');
Route::post('likeStoreByWifiId', 'API\StoreController@likeStoreByWifiId');
Route::get('searchUser', 'API\StoreController@searchUser');
Route::post('sendBalance', 'API\StoreController@sendBalance');
Route::post('doTransfer', 'API\StoreController@doTransfer');
Route::post('openStatus', 'API\StoreController@openStatus');
Route::post('addColorImage', 'API\StoreController@addColorImage');

Route::post('deletStoreItem', 'API\StoreController@deletStoreItem');


Route::get('getMyNetworks/{wifi_id}', 'API\StoreController@getMyNetworks');
Route::post('addNewNetwork', 'API\StoreController@addNewNetwork');
Route::post('editNetwork', 'API\StoreController@editNetwork');
Route::get('removeNetwork/{network_id}', 'API\StoreController@removeNetwork');
Route::get('getNetworksRequest', 'API\StoreController@getNetworksRequest');

Route::get('getMyNetworkCards/{network_id}', 'API\StoreController@getMyNetworkCards');
Route::post('addNewNetworkCard', 'API\StoreController@addNewNetworkCard');
Route::post('deleteNetworkCard/{id}', 'API\StoreController@deleteNetworkCard');
Route::get('getRenewCardRequest', 'API\StoreController@getRenewCardRequest');
Route::post('updateCardRequest/{id}', 'API\StoreController@updateCardRequest');
Route::post('addNewNetworkFile', 'API\StoreController@addNewNetworkFile');
Route::get('startImport', 'WEB\Admin\NetworksController@startImport');

Route::post('sendNotification', 'API\StoreController@sendNotification');

//   CartController
Route::get('/getMyCart','API\CartController@getMyCart');
Route::post('/addProductToCart/{id}','API\CartController@addProductToCart');
Route::post('/changeQuantity/{id}','API\CartController@changeQuantity');
Route::get('/deleteProductCart/{id}','API\CartController@deleteProductCart');
Route::get('/deleteCartItems','API\CartController@deleteCartItems');

//ChatController
      Route::post('/sendChatMsgToAdmin', 'API\ChatController@sendChatMsgToAdmin');
      Route::get('/myChatMsgWithAdmin', 'API\ChatController@myChatMsgWithAdmin');







