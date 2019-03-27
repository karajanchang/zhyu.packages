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
];
