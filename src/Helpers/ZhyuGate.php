<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-26
 * Time: 13:26
 */

namespace Zhyu\Helpers;

use Illuminate\Support\Facades\Gate;

class ZhyuGate
{
    public function init()
    {
        Gate::before(function($user, $ability){
            $user_ids = explode(',', env('ZHYU_ADMIN_USER_IDS'));
            if(in_array($user->id, $user_ids)){
                return true;
            }
        });

        Gate::define('superadmin-only', function ($user) {
//            return $user->id ===2;
        });
    }
}