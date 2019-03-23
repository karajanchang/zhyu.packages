<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:28
 */

Route::get('/ajax/{model}-{key}/{limit?}', 'Zhyu\Controller\AjaxController@index')->name('ajax');

Route::get('/lang/{locale}', function ($locale) {
    session()->put('locale', $locale);
    return Redirect::back();
})->middleware('web');

Route::get('/logout', function() {
    auth()->logout();
    return Redirect::to('/');
})->middleware('web')->name('logout');

Route::resource('/resource', 'Zhyu\Controller\ResourceController');