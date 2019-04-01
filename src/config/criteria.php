<?php
return [
    'resources' => [
        'ajax' => [
            \Zhyu\Repositories\Criterias\Common\OrderByOrderbyDesc::class,
            'select' => [
                'id', 'name', 'route', 'parent_id', 'orderby', 'icon_css'
            ],
        ],
    ],
    'user' => [
        'ajax' => [
            \Zhyu\Repositories\Criterias\Common\OrderByIdAesc::class,
        ],
    ],
    'usergroup' => [
        'ajax' => [
            \Zhyu\Repositories\Criterias\Usergroups\IsParent::class,
            \Zhyu\Repositories\Criterias\Common\OrderByIdAesc::class,
        ],
    ],
];
