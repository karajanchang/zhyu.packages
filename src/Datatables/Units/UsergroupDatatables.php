<?php

namespace Zhyu\Datatables\Units;

use Zhyu\Datatables\AbstractDatatables;
use Zhyu\Datatables\DatatablesInterface;
use App\Usergroup;

class UsergroupDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return new Usergroup();
    }

    public function config(){
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

