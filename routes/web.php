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


//Route::get('/home', function () {
//    return redirect('/');
//});


use App\Models\MobileNetworkPackages;
use App\Models\GameRequest;
use App\Models\Api;

    Route::get('/cron', function(){
        Artisan::call('UpdateApiGameRequestStatus:update');
    });


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath'
    ]
], function () {
    Route::get('/privacy_policy', 'HomeController@privacy');
    Route::get('/', 'HomeController@index');
    Route::post('/contactUs', 'HomeController@sendContact')->name('contactUs');


    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::post('/login', 'Auth\LoginController@login')->name('login');


    Route::get('reset_successfully', 'WEB\Site\SiteController@success');
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');


    Auth::routes();


    //ADMIN AUTH ///

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', function () {
            return route('/login');
        });


        Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('admin.login.form');
        Route::post('/login', 'AdminAuth\LoginController@login')->name('admin.login');
        Route::post('/logout', 'AdminAuth\LoginController@logout')->name('admin.logout');
        //  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
        //   Route::post('/password/email', 'AdminAuth\ForgotPasswordController@send_email')->name('admin.password.email');
    });


    Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin', 'as' => 'admin.',], function () {

        Route::get('/', function () {
            return redirect('/admin/home');
        });

        Route::post('/changeStatus/{model}', 'WEB\Admin\HomeController@changeStatus');
        Route::get('home', 'WEB\Admin\HomeController@index')->name('admin.home');
        Route::get('/subcategoryByCategory/{id}/', 'WEB\Admin\SubCategoryController@subcategoryByCategory');

        if (can('profit'))
        {
            Route::get('/user_prof', 'WEB\Admin\UserProfController@index')->name('user_prof');
            Route::post('/user_prof/store', 'WEB\Admin\UserProfController@store');
        Route::get('/profit', 'WEB\Admin\ProfitController@index')->name('profit.home');
            Route::get('/profit_tr', 'WEB\Admin\ProfitController@index1');
            Route::get('/profit_tr/create', 'WEB\Admin\ProfitController@create');
            Route::post('/profit_tr/store', 'WEB\Admin\ProfitController@store');
            Route::get('/profit_tr/{id}/edit', 'WEB\Admin\ProfitController@edit');
            Route::patch('/profit_tr/{id}', 'WEB\Admin\ProfitController@update');


            Route::post('/profit_reset', 'WEB\Admin\ProfitController@reset')->name('profit.reset');
            Route::get('/capital', 'WEB\Admin\CapitalController@index')->name('profit.capital');
        }
        if (can('Expenses')) {
            Route::resource('/Expenses', 'WEB\Admin\ExpensesController');
            }
        if (can('AshabGames')) {
               Route::resource('/ashab', 'WEB\Admin\AshabGamesController');
               Route::resource('/gamecard', 'WEB\Admin\GamesCardsController');
               Route::post('/gamecard/store', 'WEB\Admin\GamesCardsController@store');
           }
        if (can('AshabRequest')) {
            Route::resource('/AshabRequest', 'WEB\Admin\AshabRequestController');
            Route::resource('/AshabLog', 'WEB\Admin\AshabLogController');
        }
        if (can('MobileCompany')) {
            Route::resource('/MobileCompany', 'WEB\Admin\MobileCompaniesController');
        }
        if (can('MobileNetworkPackages')) {
            Route::resource('/MobileNetworkPackages', 'WEB\Admin\MobileNetworkPackagesController');
        }
        if (can('person_mac')) {
            Route::get('/person_mac', 'WEB\Admin\person_mac_Controller@index');
            Route::get('/person_mac/create', 'WEB\Admin\person_mac_Controller@create');
            Route::get('/person_mac/{id}/edit', 'WEB\Admin\person_mac_Controller@edit');
            Route::post('/person_mac/store', 'WEB\Admin\person_mac_Controller@store');
            Route::patch('/person_mac/{id}', 'WEB\Admin\person_mac_Controller@update');
        }

        if (can('Recharge')) {
            Route::get('/Recharge', 'WEB\Admin\RechargeInfoController@index');
            Route::get('/Recharge/create', 'WEB\Admin\RechargeInfoController@create');
            Route::get('/Recharge/{id}/edit', 'WEB\Admin\RechargeInfoController@edit');
            Route::post('/Recharge/store', 'WEB\Admin\RechargeInfoController@store');
            Route::patch('/Recharge/{id}', 'WEB\Admin\RechargeInfoController@update');
        }

        if (can('Whatsapp')) {
            Route::get('/Whatsapp', 'WEB\Admin\WhatsappController@index');
            Route::get('/Whatsapp/create', 'WEB\Admin\WhatsappController@create');
            Route::get('/Whatsapp/{id}/edit', 'WEB\Admin\WhatsappController@edit');
            Route::post('/Whatsapp/store', 'WEB\Admin\WhatsappController@store');
            Route::patch('/Whatsapp/{id}', 'WEB\Admin\WhatsappController@update');
        }
        if (can('mac')) {
            Route::get('/mac', 'WEB\Admin\Mac_Controller@index');
            Route::get('/mac/create', 'WEB\Admin\Mac_Controller@create');
            Route::get('/mac/{id}/edit', 'WEB\Admin\Mac_Controller@edit');
            Route::post('/mac/store', 'WEB\Admin\Mac_Controller@store');
            Route::patch('/mac/{id}', 'WEB\Admin\Mac_Controller@update');
        }
        if (can('NetworkSections')) {
                Route::resource('/NetworkSections', 'WEB\Admin\NetworkSectionsController', ['except' => 'index','except' => 'show']);
                Route::get('NetworkSections/{id?}', [
                    'as' => 'NetworkSections.index',
                    'uses' => 'WEB\Admin\NetworkSectionsController@index'
                ]);
            Route::patch('/NetworkSections/update_status/{id}', 'WEB\Admin\NetworkSectionsController@update_status');


//        Route::get('/NetworkSections/{id?}', 'WEB\Admin\NetworkSectionsController@index');
//        Route::get('/NetworkSections/create/{id?}', 'WEB\Admin\NetworkSectionsController@create');
//        Route::get('/NetworkSections/{id}/edit', 'WEB\Admin\NetworkSectionsController@edit');
//        Route::post('/NetworkSections/store', 'WEB\Admin\NetworkSectionsController@store');
//        Route::patch('/NetworkSections/{id}', 'WEB\Admin\NetworkSectionsController@update');


        }
        if (can('admins')) {
            Route::get('/admins', 'WEB\Admin\AdminController@index')->name('admins.all');
            Route::post('/admins', 'WEB\Admin\AdminController@store')->name('admins.store');
            Route::get('/admins/create', 'WEB\Admin\AdminController@create')->name('admins.create');
            Route::get('/admins/get_cities/{id}', 'WEB\Admin\AdminController@get_cities')->name('admins.get_cities');
            Route::get('/admins/get_cities_institutes/{id}', 'WEB\Admin\AdminController@get_cities_institutes')->name('admins.get_cities_institutes');
            Route::get('/admins/get_cities_public_services/{id}', 'WEB\Admin\AdminController@get_cities_public_services')->name('admins.get_cities_public_services');
            Route::get('/admins/{id}/edit', 'WEB\Admin\AdminController@edit')->name('admins.edit');
            Route::patch('/admins/{id}', 'WEB\Admin\AdminController@update')->name('users.update');
            Route::get('/admins/{id}/edit_password', 'WEB\Admin\AdminController@edit_password')->name('admins.edit_password');
            Route::post('/admins/{id}/edit_password', 'WEB\Admin\AdminController@update_password')->name('admins.edit_password');

        }
        if (can('financial')) {
            Route::middleware('AdminPermission:promotions')->group(function () {
                Route::resource('/financial', 'WEB\Admin\FinancialController');
            });
        }
        if (can('stores')) {
            Route::middleware('AdminPermission:stores')->group(function () {
                Route::get('/stores', 'WEB\Admin\StoreController@index')->name('stores.all');
                Route::post('/stores', 'WEB\Admin\StoreController@store')->name('stores.store');
                Route::get('/stores/create', 'WEB\Admin\StoreController@create')->name('stores.create');
                Route::delete('stores/{id}', 'WEB\Admin\StoreController@destroy')->name('stores.destroy');
                Route::get('/stores/{id}/edit', 'WEB\Admin\StoreController@edit')->name('stores.edit');
                Route::get('/stores/{id}/show', 'WEB\Admin\StoreController@show')->name('stores.show');

                Route::patch('/stores/{id}', 'WEB\Admin\StoreController@update')->name('stores.update');
                Route::get('/stores/{id}/edit_password', 'WEB\Admin\StoreController@edit_password')->name('stores.edit_password');
                Route::post('/stores/{id}/edit_password', 'WEB\Admin\StoreController@update_password')->name('stores.edit_password');
                Route::get('/stores/{id}/chat', 'WEB\Admin\StoreController@chat')->name('stores.chat');
                Route::post('/stores/{id}/sendNotification', 'WEB\Admin\StoreController@sendNotification')->name('stores.chat');
                Route::get('/storeWallet/{id}/', 'WEB\Admin\StoreController@storeWallet')->name('stores.storeWallet');
                Route::get('/storeOrders/{id}/', 'WEB\Admin\StoreController@storeOrders')->name('stores.storeOrders');
                Route::get('/storeProducts/{id}/', 'WEB\Admin\StoreController@storeProducts')->name('stores.storeProducts');
                Route::get('/stores/{id}/createStoreProduct', 'WEB\Admin\StoreController@createStoreProduct')->name('stores.createStoreProduct');
                Route::post('/stores/{id}/storeStoreProduct', 'WEB\Admin\StoreController@storeStoreProduct')->name('stores.storeStoreProduct');
                Route::get('/storeCategories/{id}/', 'WEB\Admin\StoreController@storeCategories')->name('stores.storeCategories');
                Route::get('/storeNews/{id}/', 'WEB\Admin\StoreController@storeNews')->name('stores.storeNews');
                Route::get('/storeSliders/{id}/', 'WEB\Admin\StoreController@storeSliders')->name('stores.storeSliders');
                Route::patch('/stores/update_status_cart/{id}', 'WEB\Admin\StoreController@update_status_cart');


            });
        }
        if (can('users')) {
            Route::middleware('AdminPermission:users')->group(function () {
                Route::get('/not_active_users', 'WEB\Admin\UsersController@indexNotActive')->name('users.all');
                Route::get('/users', 'WEB\Admin\UsersController@index')->name('users.all');
                Route::post('/users', 'WEB\Admin\UsersController@store')->name('users.store');
                Route::get('/users/create', 'WEB\Admin\UsersController@create')->name('users.create');
                Route::delete('users/{id}', 'WEB\Admin\UsersController@destroy')->name('users.destroy');
                Route::get('/users/{id}/edit', 'WEB\Admin\UsersController@edit')->name('users.edit');
                Route::get('/users/{id}/info', 'WEB\Admin\UsersController@info')->name('users.info');

                Route::patch('/users/{id}', 'WEB\Admin\UsersController@update')->name('users.update');
                Route::get('/users/{id}/edit_password', 'WEB\Admin\UsersController@edit_password')->name('users.edit_password');
                Route::post('/users/{id}/edit_password', 'WEB\Admin\UsersController@update_password')->name('users.edit_password');
                Route::get('/users/{id}/chat', 'WEB\Admin\UsersController@chat')->name('users.chat');
                Route::post('/users/{id}/sendNotification', 'WEB\Admin\UsersController@sendNotification')->name('users.chat');
                Route::get('/users/{id}/wallet', 'WEB\Admin\UsersController@wallet')->name('users.wallet');

            });
        }
        if (can('contact-us')) {
            Route::middleware('AdminPermission:contact-us')->group(function () {
                Route::get('/contact', 'WEB\Admin\ContactController@index');
                Route::get('/viewMessage/{id}', 'WEB\Admin\ContactController@viewMessage');
                Route::delete('/contact/{id}', 'WEB\Admin\ContactController@destroy');

            });
        }
        if (can('settings')) {
            Route::middleware('AdminPermission:settings')->group(function () {
                Route::get('settings', 'WEB\Admin\SettingController@index')->name('settings.all');
                Route::post('settings', 'WEB\Admin\SettingController@update')->name('settings.update');

            });

        }
        if (can('pages')) {
            Route::middleware('AdminPermission:pages')->group(function () {
                Route::resource('/pages', 'WEB\Admin\PagesController');
                Route::post('/pages/changeStatus', 'WEB\Admin\PagesController@changeStatus');

            });

        }
        if (can('chat')) {
            Route::middleware('AdminPermission:chat')->group(function () {
                Route::get('/chat', 'WEB\Admin\ChatController@chat_all_user');
                Route::get('/new_message/{id}/response', 'WEB\Admin\ChatController@new_message');
                Route::post('/new_message', 'WEB\Admin\ChatController@new_message_admin');

            });

        }
        if (can('promotions')) {
            Route::middleware('AdminPermission:promotions')->group(function () {
                Route::resource('/promotions', 'WEB\Admin\Promotion_codeController');
                Route::post('promotions_changeStatus', 'WEB\Admin\Promotion_codeController@changeStatus');

            });


        }
        if (can('products')) {
            Route::middleware('AdminPermission:products')->group(function () {
                Route::resource('/products', 'WEB\Admin\ProductsController');
                // Route::post('/products/{id}', 'WEB\Admin\ProductsController@update');
                Route::get('/importProducts', 'WEB\Admin\ImportProductsController@index');
                Route::get('/fils/create', 'WEB\Admin\ImportProductsController@create');
                Route::post('/fils/store', 'WEB\Admin\ImportProductsController@store');


                Route::get('/productImages', 'WEB\Admin\ProductsController@productImages');
                Route::get('/addImages', 'WEB\Admin\ProductsController@addImages');
                Route::post('/storeImage', 'WEB\Admin\ProductsController@storeImage');
                Route::get('/deleteImage', 'WEB\Admin\ProductsController@deleteImage');
                Route::get('/removeImageFromDropZone', 'WEB\Admin\ProductsController@removeImageFromDropZone');

            });


        }
        if (can('sliders')) {
         Route::middleware('AdminPermission:sliders')->group(function(){
        Route::resource('/sliders', 'WEB\Admin\SliderController', ['except' => 'index','except' => 'show']);
        Route::get('sliders/{id?}', [
            'as' => 'sliders.index',
            'uses' => 'WEB\Admin\SliderController@index'
        ]);
        Route::get('/slider/sorting/{id?}', 'WEB\Admin\SliderController@sorting');
        Route::post('/slider/sort', 'WEB\Admin\SliderController@sort');
        });
        }
        if (can('distribution_points')) {
            Route::middleware('AdminPermission:distribution_points')->group(function () {
            Route::resource('/distribution_points', 'WEB\Admin\DistributionPointsController');
            Route::get('/distribution_points/sorting', 'WEB\Admin\DistributionPointsController@sorting');
            Route::post('/distribution_points/sort', 'WEB\Admin\DistributionPointsController@sort');
            });

        }
        if (can('news')) {
        Route::middleware('AdminPermission:news')->group(function(){
        Route::resource('/news', 'WEB\Admin\NewsController', ['except' => 'index','except' => 'show']);
        Route::get('news/{id?}', [
            'as' => 'news.index',
            'uses' => 'WEB\Admin\NewsController@index'
        ]);
        Route::get('/newss/sorting/{id?}', 'WEB\Admin\NewsController@sorting');
        Route::post('/newss/sort', 'WEB\Admin\NewsController@sort');
        });
        }
        if (can('categories')) {
            Route::middleware('AdminPermission:categories')->group(function () {
                Route::resource('/categories', 'WEB\Admin\CategoriesController');
                Route::get('/category/sorting', 'WEB\Admin\CategoriesController@sorting');
                Route::post('/category/sort', 'WEB\Admin\CategoriesController@sort');
            });
        }
        if (can('allStoreCategories')) {
            Route::middleware('AdminPermission:categories')->group(function () {
                Route::resource('/allStoreCategories', 'WEB\Admin\StoreCategoriesController');
                Route::get('/allStoreCategories/sorting', 'WEB\Admin\StoreCategoriesController@sorting');
                Route::post('/allStoreCategories/sort', 'WEB\Admin\StoreCategoriesController@sort');
            });
        }
        if (can('subcategories')) {
            Route::middleware('AdminPermission:subcategories')->group(function () {
                Route::resource('/subcategories', 'WEB\Admin\SubCategoryController');
                Route::get('/getSubCategoryByCategoryId/{id}', 'WEB\Admin\SubCategoryController@getCategory');
                Route::get('/subcategory/sorting', 'WEB\Admin\SubCategoryController@sorting');
                Route::post('/subcategory/sort', 'WEB\Admin\SubCategoryController@sort');
            });

        }
        if (can('azkar')) {
            Route::middleware('AdminPermission:azkar')->group(function () {
                Route::resource('/azkar', 'WEB\Admin\AzkarController');
                Route::resource('/azkarDetails', 'WEB\Admin\AzkarDetailsController');
                Route::get('/azkars/sorting', 'WEB\Admin\AzkarController@sorting');
                Route::post('/azkars/sort', 'WEB\Admin\AzkarController@sort');

            });

        }
        if (can('service')) {
            Route::middleware('AdminPermission:service')->group(function () {
                Route::resource('/services', 'WEB\Admin\ServiceController');

            });
        }
        if (can('sizes')) {
            Route::middleware('AdminPermission:sizes')->group(function () {
                Route::resource('/sizes', 'WEB\Admin\SizeController');

            });
        }
        if (can('colors')) {
            Route::middleware('AdminPermission:colors')->group(function () {
                Route::resource('/colors', 'WEB\Admin\ColorController');

            });
        }
        if (can('games')) {
            Route::middleware('AdminPermission:games')->group(function () {
                Route::resource('/games', 'WEB\Admin\GameController');
                Route::resource('/gameServies', 'WEB\Admin\GameServiesController');
                Route::resource('/nagma_game', 'WEB\Admin\NagmaGameController');

            });
        }
        if (can('WhatsAppProductServiceRequests')) {
            Route::resource('/ProductServiceRequests', 'WEB\Admin\ProductServiceRequestsController');
        }
        if (can('gameRequest')) {
            Route::middleware('AdminPermission:gameRequest')->group(function () {
                Route::resource('/gameRequest', 'WEB\Admin\GameRequestController');

            });
        }
        if (can('institutes')) {
            Route::middleware('AdminPermission:institutes')->group(function () {
                Route::resource('/institutes', 'WEB\Admin\InstituteController');
                Route::resource('/instituteCourses', 'WEB\Admin\InstituteCoursesController');

            });
        }
        if (can('courseRequest')) {
            Route::middleware('AdminPermission:courseRequest')->group(function () {

                Route::resource('/courseRequest', 'WEB\Admin\CourseRequestController');

            });
        }
        if (can('publicServices')) {
            Route::middleware('AdminPermission:publicServices')->group(function () {
                Route::resource('/publicServices', 'WEB\Admin\PublicServicesController');

            });
        }
        if (can('publicServicesRequest')) {
            Route::middleware('AdminPermission:publicServicesRequest')->group(function () {
                Route::resource('/publicServicesRequest', 'WEB\Admin\PublicServicesRequestController');

            });
        }
        if (can('requestRenewCard')) {
            Route::middleware('AdminPermission:requestRenewCard')->group(function () {
                Route::resource('/requestRenewCard', 'WEB\Admin\RequestRenewCardController', ['except' => 'index','except' => 'show']);
                Route::get('requestRenewCard/{id?}', [
                    'as' => 'requestRenewCard.index',
                    'uses' => 'WEB\Admin\RequestRenewCardController@index'
                ]);
            });
        }
        if (can('orders')) {
            Route::middleware('AdminPermission:orders')->group(function () {
                Route::resource('/orders', 'WEB\Admin\OrdersController');
                Route::get('orders/printOrder/{id}', 'WEB\Admin\OrdersController@printOrder');


            });
        }
        if (can('soldServices')) {
            Route::resource('/soldServices', 'WEB\Admin\ServiceCardsRequestController');
        }
        if (can('productServices')) {
            Route::middleware('AdminPermission:productServices')->group(function () {
                Route::resource('/productServices', 'WEB\Admin\ProductServiceController');
                Route::get('/productServicesCards/{id}/', 'WEB\Admin\ProductServiceController@getCards');
                Route::get('/productServices/{id}/addCards', 'WEB\Admin\ProductServiceController@addCards');
                Route::post('/productServices/{id}/addCards', 'WEB\Admin\ProductServiceController@storeCards');
                Route::post('/productServices/{id}/cards', 'WEB\Admin\ProductServiceController@storeCards');
            });

        }
        if (can('cities')) {
            Route::middleware('AdminPermission:cities')->group(function () {
                Route::resource('/cities', 'WEB\Admin\CitiesController');

            });
        }

        if (can('countrie')) {
            Route::get('/countries', 'WEB\Admin\CountryController@index');
            Route::get('/countries/create', 'WEB\Admin\CountryController@create');
            Route::post('/countries/store', 'WEB\Admin\CountryController@store');
            Route::get('/countries/{id}/edit', 'WEB\Admin\CountryController@edit');
            Route::patch('/countries/{id}', 'WEB\Admin\CountryController@update');
        }

        if (can('apk')) {
            Route::middleware('AdminPermission:apk')->group(function () {
                Route::get('/apk', 'WEB\Admin\ApkController@index');
                Route::get('/apk/create', 'WEB\Admin\ApkController@create');
                Route::post('/apk/store', 'WEB\Admin\ApkController@store');

            });
        }
        if (can('wifi')) {
            Route::middleware('AdminPermission:wifi')->group(function () {
                Route::resource('/wifi', 'WEB\Admin\WifiController', ['except' => 'index','except' => 'show']);
                Route::get('wifi/{id?}', [
                    'as' => 'wifi.index',
                    'uses' => 'WEB\Admin\WifiController@index'
                ]);
                Route::get('/wifi/{id}/chat', 'WEB\Admin\WifiController@chat')->name('users.chat');
                Route::post('/wifi/{id}/sendNotification', 'WEB\Admin\WifiController@sendNotification')->name('users.chat');
            });

        }
        if (can('networks')) {
            Route::middleware('AdminPermission:networks')->group(function () {
                Route::resource('/networks', 'WEB\Admin\NetworksController', ['except' => 'index','except' => 'show']);
                Route::get('networks/{id?}', [
                    'as' => 'networks.index',
                    'uses' => 'WEB\Admin\NetworksController@index'
                ]);
                Route::get('/cards/{id}/', 'WEB\Admin\NetworksController@getCards');
                Route::get('/networks/{id}/addCards', 'WEB\Admin\NetworksController@addCards');
                Route::post('/networks/{id}/addCards', 'WEB\Admin\NetworksController@storeCards');
                Route::post('/networks/{id}/cards', 'WEB\Admin\NetworksController@storeCards');


                Route::get('/fils/{id}/add', 'WEB\Admin\NetworksController@addFile');
                Route::post('/fils/{id}/store', 'WEB\Admin\NetworksController@storeFile');
            });


        }
        Route::resource('/nagma_ashab', 'WEB\Admin\NagmaAshabController');
        Route::get('/nagma_ashab/get_all_cards/{id}', 'WEB\Admin\NagmaAshabController@get_all_cards');
        if (can('balanceCards')) {
            Route::middleware('AdminPermission:balanceCards')->group(function () {
                Route::resource('/balanceCards', 'WEB\Admin\BalanceCardsController');

            });

        }
        if (can('newbalanceCards')) {
            Route::middleware('AdminPermission:balanceCards')->group(function () {
            Route::get('/newbalanceCards', 'WEB\Admin\BalanceCardsController@newbalanceCards');
            });
        }
        if (can('requestMobileBalance')) {
            Route::middleware('AdminPermission:requestMobileBalance')->group(function () {
                Route::resource('/requestMobileBalance', 'WEB\Admin\RequestMobileBalanceController');
            });
        }
        if (can('ads')) {
            Route::middleware('AdminPermission:ads')->group(function () {
                Route::resource('/ads', 'WEB\Admin\AdsController');
                Route::get('/ad/sorting', 'WEB\Admin\AdsController@sorting');
                Route::post('/ad/sort', 'WEB\Admin\AdsController@sort');
            });

        }
        if (can('apis')) {
            Route::middleware('AdminPermission:ads')->group(function () {
                Route::resource('/apis', 'WEB\Admin\ApiController');
            });

        }
        if (can('orders')) {
            Route::middleware('AdminPermission:orders')->group(function () {
                Route::resource('orders', 'WEB\Admin\OrdersController');
                Route::get('orders/orderDetails/{id}', 'WEB\Admin\OrdersController@orderDetails');
                Route::post('/change_orderSts/{id}', 'WEB\Admin\OrdersController@change_orderSts');
            });

        }
        if (can('questions')) {
            Route::middleware('AdminPermission:questions')->group(function () {
                Route::resource('questions', 'WEB\Admin\QuestionsController');
                Route::post('/questions/{id}', 'WEB\Admin\QuestionsController@update');
            });

        }
        if (can('notifications')) {
            Route::middleware('AdminPermission:notifications')->group(function () {
                Route::resource('/notifications', 'WEB\Admin\NotificationMessageController');

            });
        }
        if (can('roles')) {
            Route::resource('/roles', 'WEB\Admin\RoleController');
        }
        ////////////
        if (can('rates')) {
            Route::middleware('AdminPermission:rates')->group(function () {
                Route::resource('rates', 'WEB\Admin\RateController');
                Route::get('rates/rateDetails/{id}', 'WEB\Admin\RateController@rateDetails');
            });

        }
        
        
        if (can('owners')) {
            Route::resource('/owners', 'WEB\Admin\OwnersController');
            Route::get('/owners/{id}/edit_password', 'WEB\Admin\OwnersController@edit_password')->name('owners.edit_password');
            Route::post('/owners/{id}/edit_password', 'WEB\Admin\OwnersController@update_password')->name('owners.edit_password');
        }
        
        
        if (can('promo_codes')) {
            Route::resource('/promo_codes', 'WEB\Admin\PromoCodeController');
        }
        ///////////
        Route::get('/getDataFromApi/{id}', 'WEB\Admin\GameController@getDataFromApi');
    });
    Route::get('/userexport', 'WEB\Admin\UsersController@export')->name('users.all');
});
Route::get('files/startImport', 'WEB\Admin\NetworksController@startImport');
Route::get('/import', 'WEB\Admin\ImportProductsController@import');

      
