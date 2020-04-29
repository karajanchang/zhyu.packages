<?php

namespace Zhyu\Datatables\Configs;

use Zhyu\Datatables\AbstractDatatables;
use Zhyu\Datatables\DatatablesInterface;
use App\Usergroup;

class UsergroupDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return Usergroup::class;
    }

    /**
     * Set custom act.
     *
     * @return string
     */
    public function act(){
        return 'ajax';
    }

    public function criteria(): array
    {
        return [];
    }

    public function config() : array{
        return [
            'id' =>  'myTable',
            'css' =>  [ 'table', 'manage-u-table', 'table-striped', 'dataTable', 'nowrap' ],
            'searchable_cols' => [ 'name', 'nologin'],
            'no_orderable_cols' => [],
            'cols_display' => [
                'name' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'is_online' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'nologin' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'buttons' => [
                    'title' => '',
                ],
            ],
        ];
    }

}

