First: How to install?
    1.composer require zhyu/packages
    2.php artisan vendor:publish --tag=zhyu --force
    3.create fllow config in .env file
        #---admin to manage resources
        ZHYU_ADMIN_USER_IDS=1
    4.append these lines in app\Providers\AuthServiceProvider.php boot method
        ZhyuGate::init();


Second: Rules



    1.datatable ajax url parameters rules
        /ajax/{model}-{key}/{limit?}?query=UserName,=,david*UserPhone,=,0233223333
            ?query=parent_id,=,0
            ?query=parent_id,whereNull

Others:
    2.create reoureces route
    /resources

