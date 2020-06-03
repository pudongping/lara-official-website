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
    'as' => 'admin.'
], function () {
    // 用户名/邮箱/手机号/登录
    Route::post('authorizations', 'Auth\AdminsController@login')->name('authorizations.login');
});

Route::group([
    'middleware' => ['throttle:' . config('api.rate_limits.access')],  // 1分钟/60次
    'as' => 'admin.'
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
            Route::put('settings/update', 'Setting\SettingsController@update')->name('settings.update');  // 更新站点设置
        });

        Route::get('user', 'Auth\AdminsController@me')->name('user.show');  // 当前登录用户信息
        Route::put('authorizations/current', 'Auth\AdminsController@refreshToken')->name('authorizations.refreshToken');  // 刷新token
        Route::delete('authorizations/current', 'Auth\AdminsController@logout')->name('authorizations.logout');  // 删除token
        Route::patch('users/{user}', 'Auth\AdminsController@update')->name('users.update');  // 编辑登录用户信息-数据处理

        // =======================系统相关=========================
        Route::get('clearCache', 'Setting\SettingsController@clearCache')->name('settings.clearCache');  // 清空所有缓存
        Route::resource('menus', 'Setting\MenusController')->except(['create', 'show']);  // 菜单
        Route::resource('banners', 'Setting\BannersController')->only(['index', 'store', 'update', 'destroy']);  // banner 图

        // =======================工具相关=========================
        Route::post('images', 'Common\ImagesController@store')->name('images.store');  // 上传图片
        Route::get('prizes/probably', 'Common\PrizesController@probably')->name('prizes.probably');  // 抽奖概率测试

        // =======================商品相关=========================
        Route::group(['prefix' => 'product'], function () {
            Route::get('categories', 'Product\ProductCategoryController@index')->name('product.categories.index');  // 商品类目列表
            Route::post('categories', 'Product\ProductCategoryController@store')->name('product.categories.store');  // 新建类目
            Route::get('categories/{category}/edit', 'Product\ProductCategoryController@edit')->name('product.categories.edit');  // 编辑显示类目
            Route::patch('categories/{category}', 'Product\ProductCategoryController@update')->name('product.categories.update');  // 编辑类目-数据提交
            Route::delete('categories/{category}', 'Product\ProductCategoryController@destroy')->name('product.categories.destroy');   // 删除类目
            Route::get('categories/fetchByLevel', 'Product\ProductCategoryController@fetchByLevel')->name('product.categories.fetchByLevel');  // 获取指定层级的分类数据
            Route::post('categories/changeIndexShow', 'Product\ProductCategoryController@changeIndexShow')->name('product.categories.changeIndexShow');  // 改变分类是否显示在首页

            Route::get('brands', 'Product\ProductBrandController@index')->name('product.brands.index');  // 商品品牌列表
            Route::post('brands', 'Product\ProductBrandController@store')->name('product.brands.store');   // 新建品牌
            Route::get('brands/{brand}/edit', 'Product\ProductBrandController@edit')->name('product.brands.edit');  // 编辑显示类目
            Route::patch('brands/{brand}', 'Product\ProductBrandController@update')->name('product.brands.update');  // 编辑品牌数据提交
            Route::delete('brands/{brand}', 'Product\ProductBrandController@destroy')->name('product.brands.destroy');  // 删除品牌

            Route::get('spus', 'Product\ProductSpuController@index')->name('product.spus.index');  // 商品列表
            Route::post('spus', 'Product\ProductSpuController@store')->name('product.spus.store');  // 添加主商品
            Route::get('spus/{spu}/edit', 'Product\ProductSpuController@edit')->name('product.spus.edit');  // 编辑显示主商品
            Route::patch('spus/{spu}', 'Product\ProductSpuController@update')->name('product.spus.update');  // 编辑主商品数据提交
            Route::get('spus/{spu}', 'Product\ProductSpuController@show')->name('product.spus.show');  // 商品详情
            Route::put('spus/{spu}/description', 'Product\ProductSpuController@modifyDescription')->name('product.spus.modifyDescription');  // 商品更新描述信息
            Route::get('spus/{spu}/getSkusTemplate', 'Product\ProductSpuController@getSkusTemplate')->name('product.spus.getSkusTemplate');  // 获取 sku 数据模板
            Route::post('spus/{spu}/attrOptUpdate', 'Product\ProductSpuController@attrOptStoreOrUpdate')->name('product.spus.attrOptStoreOrUpdate');  // 添加 「属性-属性选项值」 或者 更新 「属性-属性选项值」
            Route::post('spus/{spu}/skus', 'Product\ProductSpuController@skuStoreOrUpdate')->name('product.spus.skuStoreOrUpdate');  // 添加 sku 数据 或者 更新 sku 数据
            Route::post('spus/changeHot', 'Product\ProductSpuController@changeHot')->name('product.spus.changeHot');  // 改变商品是否为爆款
        });

    });
});
