<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {
    Route::get('/', 'WelcomeController@welcome')->name('welcome');
    Route::get('/terms', 'TermsController@terms')->name('terms');

    Route::get('/v/{reference}/{lost_date}', 'InvestigationController@public_view')->name('public_case_view');

    //Signed URLs that are "public"
    Route::post('{reference}/images', 'InvestigationController@store_images')->name('store_images');
    Route::delete('{reference}/images/{image}', 'InvestigationController@destroy_image')->name('destroy_image');
});

// Authentication Routes (with self registration disabled)
Auth::routes(['register' => false]);

// Public Routes
Route::group(['middleware' => ['web', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'twostep', 'checkblocked']], function () {

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);

    //Routes for case management
    Route::prefix('case')->group(function () {

        Route::get('/list/{view}/{list?}/{date?}', 'InvestigationController@list')->name('list_case');

        //This is the endpoints for the search function (paginated)
        Route::get('/search', 'SearchController@item_search')->name('item_search');
        Route::post('/search/show_results', 'SearchController@show_results')->name('show_results_search');

        Route::prefix('items')->group(function () {
            Route::post('/', 'InvestigationController@store_item')->name('store_item');
            Route::get('/create', 'InvestigationController@create_item')->name('create_item');

            Route::get('today', function () {

            });
            Route::get('cancelled', function () {

            });
            Route::get('police', function () {

            });
            Route::get('evicted', function () {

            });
        });

        Route::prefix('lost')->group(function () {
            Route::post('/', 'InvestigationController@store_lost')->name('store_lost');
            Route::get('/create', 'InvestigationController@create_lost')->name('create_lost');

            Route::get('active', function () {

            });
            Route::get('today', function () {

            });
            Route::get('cancelled', function () {

            });
        });

        Route::prefix('waiting')->group(function () {
            Route::get('delivery', function () {

            });
            Route::get('send', function () {

            });
        });

        Route::delete('{reference}', 'InvestigationController@destroy')->name('destroy_case');

        Route::get('{reference}', 'InvestigationController@show')->name('show_case')->middleware('recentLog');

        Route::get('{reference}/checkalive', 'InvestigationController@check_alive')->name('check_alive');

        Route::get('{reference}/edithistory', 'InvestigationController@show_edithistory')->name('show_edithistory');

        Route::get('{reference}/edit', 'InvestigationController@edit')->name('edit_case');
        Route::put('{reference}', 'InvestigationController@update')->name('update_case');

        Route::post('{reference}/addconversation', 'ConversationController@store_message')->name('store_message');

        Route::post('{reference}/readconversation', 'ConversationController@mark_read')->name('mark_read');

        Route::post('{reference}/status/force', 'InvestigationController@update_status_force')->name('update_status_force');
        Route::post('{reference}/status/withowner', 'InvestigationController@update_status_withowner')->name('update_status_withowner');
        Route::post('{reference}/status/{wanted}', 'InvestigationController@update_status')->name('update_status');

        Route::get('{reference}/link/{reference2}', 'InvestigationController@link')->name('link');

        Route::post('{reference}/link/{reference2}', 'InvestigationController@link_update')->name('link_update');
    });
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'twostep', 'checkblocked']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController',
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'UsersManagementController@search')->name('search-users');

    Route::resource('themes', 'ThemesManagementController', [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'AdminDetailsController@listRoutes');
    Route::get('active-users', 'AdminDetailsController@activeUsers');
});

Route::redirect('/php', '/phpinfo', 301);
