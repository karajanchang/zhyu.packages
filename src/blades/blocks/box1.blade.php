@php
    echo app()->make('button.create', [
        'data' => null,
        'url' =>
            [
                $route.'.create'
            ],
        'text' => 'add',
        //'title' => 'add'
    ]);
@endphp