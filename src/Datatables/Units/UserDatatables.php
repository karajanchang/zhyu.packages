<?php

namespace Zhyu\Datatables\Units;

use Zhyu\Datatables\AbstractDatatables;
use Zhyu\Datatables\DatatablesInterface;
use App\User;

class UserDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return new User();
    }

    public function config(){
        return [
            'id' =>  'myTable',
            'css' =>  [ 'table', 'manage-u-table', 'table-striped', 'dataTable', 'nowrap' ],
            'searchable_cols' => [ 'nickname', 'name',  'email'],
            'no_orderable_cols' => [],
            'cols_display' => [
                'usergroup' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'nickname' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'name' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'email' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'is_online' => [
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

