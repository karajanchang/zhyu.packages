<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:28
 */

Route::get('/ajax/{model}-{key}/{limit?}', 'Zhyu\Controller\AjaxController@index')->name('ajax');



