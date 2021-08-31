<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('langlist', 'LocaleController@getLangList');
    Route::get('menu', 'MenuController@index');
    /* Route::get('vienna', 'ViennaSignController@index')->name('vienna.index'); */ 

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('register', 'AuthController@register'); 

    Route::resource('notes', 'NotesController');

    Route::resource('resource/{table}/resource', 'ResourceController');

    Route::prefix('vienna')->group(function () {
        Route::get('/', 'ViennaSignController@index')->name('vienna.index');
        Route::get('/signscategories' , 'ViennaSignController@getSignsCategories')->name('vienna.signscategories');
        Route::get('/{id}', 'ViennaSignController@show')->name('vienna.show');

        Route::post('/insertsign', 'ViennaSignController@store')->name('vienna.store');
        Route::post('/insertcategory', 'ViennaSignController@storeCategories')->name('vienna.storecategory');

        Route::delete('/{id}', 'ViennaSignController@delete')->name('vienna.destroy');
        Route::delete('/categories/{id}', 'ViennaSignController@deleteCategories')->name('vienna.destroycategories');

        Route::put('/categories/{id}', 'ViennaSignController@updateCategory')->name('vienna.updatecategories');
        Route::put('/{id}', 'ViennaSignController@update')->name('vienna.update');
    });

    Route::prefix('entity')->group(function () {
        Route::get('/', 'EntityController@index')->name('entity.index');

        Route::post('/insert', 'EntityController@store')->name('entity.store');

        Route::put('/{id}', 'EntityController@edit')->name('entity.update');
    });



    Route::prefix('ivisign')->group(function () {
        Route::get('/', 'IviSignMapController@index')->name('ivisign.index');
        Route::get('/ivisignbyid', 'IviSignMapController@getSignById')->name('ivisign.getsignbyid');
        Route::get('/ivisignsmarkers', 'IviSignMapController@getIviSignsMapMarkers')->name('ivisign.ivisignsmarkers');
        Route::get('/zones/{id}', 'IviSignMapController@getZones')->name('ivisign.zones');
        Route::get('/published', 'IviSignMapController@getpublishedsigns')->name('ivisign.published');
        Route::get('/unpublished', 'IviSignMapController@getunpublishedsigns')->name('ivisign.unpublished');

        Route::post('/insertivisign', 'IviSignMapController@store')->name('ivisign.store');

        Route::put('/{id}', 'IviSignMapController@update')->name('ivisign.update');
        Route::put('/updatelockstatus/{id}', 'IviSignMapController@updateLockStatus')->name('ivisign.updatelockstatus');
        Route::put('/updatecoordinates/{id}', 'IviSignMapController@updateCoordinates')->name('ivisign.updatecoordinates');
        Route::put('/publicationupdate/{id}', 'IviSignMapController@publishedUpdate')->name('ivisign.publishedUpdate');

        Route::delete('/{id}', 'IviSignMapController@destroy')->name('ivisign.destoy');
    });   


    Route::prefix('deploy')->group(function () {
        Route::get('/', 'DeployGroupController@index')->name('deploygroup.index');
        Route::get('/signsfordeploy', 'DeployGroupController@signsForDeploy')->name('signsfordeploy.signsForDeploy');

        Route::post('/insert', 'DeployGroupController@store')->name('deploygroup.store');

        Route::put('/setdeployed/{id}', 'DeployGroupController@setDeployed')->name('deployegroup.setdeployed');
    });
    

    Route::prefix('user')->group(function () {
        Route::get('/', 'UsersController@index')->name('user.index');
        Route::get('/techniciansofentity', 'UsersController@techniciansOfEntity')->name('user.usersbyentity');

        Route::post('/create', 'UsersController@create')->name('user.create');
    });
    

    Route::resource('mail',        'MailController');
        Route::get('prepareSend/{id}', 'MailController@prepareSend')->name('prepareSend');
        Route::post('mailSend/{id}',   'MailController@send')->name('mailSend');

        Route::resource('bread',  'BreadController');   //create BREAD (resource)

        Route::resource('users', 'UsersController')->except( ['create', 'store'] );
        Route::get('menu/edit', 'MenuEditController@index');
        Route::get('menu/edit/selected', 'MenuEditController@menuSelected');
        Route::get('menu/edit/selected/switch', 'MenuEditController@switch');

        
       

        
    Route::prefix('menu/element')->group(function () { 
        Route::get('/',             'MenuElementController@index')->name('menu.index');
        Route::get('/move-up',      'MenuElementController@moveUp')->name('menu.up');
        Route::get('/move-down',    'MenuElementController@moveDown')->name('menu.down');
        Route::get('/create',       'MenuElementController@create')->name('menu.create');
        Route::post('/store',       'MenuElementController@store')->name('menu.store');
        Route::get('/get-parents',  'MenuElementController@getParents');
        Route::get('/edit',         'MenuElementController@edit')->name('menu.edit');
        Route::post('/update',      'MenuElementController@update')->name('menu.update');
        Route::get('/show',         'MenuElementController@show')->name('menu.show');
        Route::get('/delete',       'MenuElementController@delete')->name('menu.delete');
    });
    Route::prefix('media')->group(function ($router) {
        Route::get('/',                 'MediaController@index')->name('media.folder.index');
        Route::get('/folder/store',     'MediaController@folderAdd')->name('media.folder.add');
        Route::post('/folder/update',   'MediaController@folderUpdate')->name('media.folder.update');
        Route::get('/folder',           'MediaController@folder')->name('media.folder');
        Route::post('/folder/move',     'MediaController@folderMove')->name('media.folder.move');
        Route::post('/folder/delete',   'MediaController@folderDelete')->name('media.folder.delete');;

        Route::post('/file/store',      'MediaController@fileAdd')->name('media.file.add');
        Route::get('/file',             'MediaController@file');
        Route::post('/file/delete',     'MediaController@fileDelete')->name('media.file.delete');
        Route::post('/file/update',     'MediaController@fileUpdate')->name('media.file.update');
        Route::post('/file/move',       'MediaController@fileMove')->name('media.file.move');
        Route::post('/file/cropp',      'MediaController@cropp');
        Route::get('/file/copy',        'MediaController@fileCopy')->name('media.file.copy');

        Route::get('/file/download',    'MediaController@fileDownload');
    });

    Route::resource('roles',        'RolesController');
    Route::get('/roles/move/move-up',      'RolesController@moveUp')->name('roles.up');
    Route::get('/roles/move/move-down',    'RolesController@moveDown')->name('roles.down');

    Route::group(['middleware' => 'admin'], function ($router) {
        
    });

    Route::post('lazyTable', 'LazyTableController@index');
});