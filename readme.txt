First: How to install?

    1.composer require zhyu/packages
    2.php artisan vendor:publish --tag=zhyu --force
    3.php artisan migrate
    4.create fllow config in .env file
        #---super admin to manage resources
        ZHYU_ADMIN_USER_IDS=1
    5.Register alias in config/app.php
        'ZhyuGate' => Zhyu\Facades\ZhyuGate::class,
    6.append these lines in app\Providers\AuthServiceProvider.php boot method
        ZhyuGate::init();

    7.make usergroup model in \App folder





Second: Rules

    1.datatable ajax url parameters rules
        /ajax/{model}-{key}/{limit?}?query=UserName:=:david*UserPhone:=:0233223333
            ?query=parent_id:=:0
            ?query=parent_id:whereNull

Others:
    2.create reoureces route
    /resources



Commands:
    php artisan make:repository {repository} --m={model}
    php artisan make:crud AAAController --r={repository} --m={model} --datatable={datatable} --route={route} --resource={resource} --act={act}
    *datatable ajax url: /ajax-{model}-{act}