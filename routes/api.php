<?php

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

/*
|--------------------------------------------------------------------------
| 后台管理相关
|--------------------------------------------------------------------------
|
*/
Route::group([
    'middleware' => ['throttle:' . config('api.rate_limits.sign')],  // 1分钟/10次
    'as' => 'admin.',
    'prefix' => 'admin'
], function () {
    // 用户名/邮箱/手机号/登录
    Route::post('authorizations', 'Auth\AdminsController@login')->name('authorizations.login');
});

Route::group([
    'middleware' => ['throttle:' . config('api.rate_limits.access')],  // 1分钟/60次
    'as' => 'admin.',
    'prefix' => 'admin'
], function () {
    // 登录之后才允许访问
    Route::group(['middleware' => ['auth:admin', 'check_admin_menus']], function () {

        // 只有超级管理员才允许访问
        Route::group(['middleware' => ['role:Administrator']], function () {
            Route::resource('roles', 'Auth\RolesController')->except('show');  // 角色
            Route::delete('rolesMassDestroy', 'Auth\RolesController@massDestroy')->name('roles.massDestroy');  // 批量删除角色
            Route::resource('permissions', 'Auth\PermissionsController')->except('show');  // 权限
            Route::delete('permissionsMassDestroy', 'Auth\PermissionsController@massDestroy')->name('permissions.massDestroy');  // 批量删除权限
            Route::get('users', 'Auth\AdminsController@index')->name('users.index');  // 管理员列表
            Route::post('users', 'Auth\AdminsController@store')->name('users.store');  // 创建新管理员-数据处理
            Route::get('users/{user}', 'Auth\AdminsController@show')->name('users.show');  // 某个用户的详情
            Route::delete('/users/{user}', 'Auth\AdminsController@destroy')->name('users.destroy');  // 删除用户
            Route::get('logs', 'Setting\LogsController@index')->name('logs.index');  // 操作日志列表
            Route::get('settings', 'Setting\SettingsController@index')->name('settings.index');  // 站点设置
            Route::put('settings/update/{setting}', 'Setting\SettingsController@update')->name('settings.update');  // 更新站点设置
        });

        Route::get('user', 'Auth\AdminsController@me')->name('user.show');  // 当前登录用户信息
        Route::put('authorizations/current', 'Auth\AdminsController@refreshToken')->name('authorizations.refreshToken');  // 刷新token
        Route::delete('authorizations/current', 'Auth\AdminsController@logout')->name('authorizations.logout');  // 删除token
        Route::patch('users/{user}', 'Auth\AdminsController@update')->name('users.update');  // 编辑登录用户信息-数据处理

        // =======================系统相关=========================
        Route::get('clearCache', 'Setting\SettingsController@clearCache')->name('settings.clearCache');  // 清空所有缓存
        Route::resource('menus', 'Setting\MenusController')->except(['create', 'show']);  // 菜单
        Route::resource('banners', 'Setting\BannersController')->only(['index', 'store', 'update', 'destroy']);  // banner 图
        Route::get('partners', 'Setting\PartnerController@index')->name('partners.index');  // 洽谈合作数据列表
        Route::patch('partners/{partner}', 'Setting\PartnerController@update')->name('partners.update');  // 更新洽谈合作信息

        // =======================工具相关=========================
        Route::post('images', 'Common\ImagesController@store')->name('images.store');  // 上传图片
        Route::post('files', 'Common\FilesController@store')->name('files.store');  // 上传文件

        // =======================文章相关=========================
        Route::post('articles', 'Article\ArticleController@store')->name('articles.store');  // 添加文章
        Route::patch('articles/{article}', 'Article\ArticleController@update')->name('articles.update');  // 更新文章
        Route::delete('articles/{article}', 'Article\ArticleController@destroy')->name('articles.destroy');  // 删除文章
        Route::get('articles/{article}', 'Article\ArticleController@show')->name('articles.show');  // 文章详情
        Route::get('articles', 'Article\ArticleController@index')->name('articles.index');  // 文章列表

    });
});



/*
|--------------------------------------------------------------------------
| 门户相关
|--------------------------------------------------------------------------
|
*/
Route::group([
    'middleware' => ['throttle:' . config('api.rate_limits.access')],  // 1分钟/60次
    'as' => 'portal.'
], function () {
    Route::get('banners', 'Portal\BannersController@index')->name('banners.index');  // 门户 banner 列表
    Route::get('settings', 'Portal\SettingsController@index')->name('settings.index');  // 系统设置信息
    Route::post('partners', 'Portal\PartnerController@store')->name('partners.store');  // 洽谈合作
    Route::get('articles', 'Portal\ArticleController@index')->name('articles.index');  // 文章列表
    Route::get('articles/{article}', 'Portal\ArticleController@show')->name('articles.show');  // 文章详情
});
