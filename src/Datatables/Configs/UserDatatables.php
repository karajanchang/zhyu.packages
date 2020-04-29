<?php

namespace Zhyu\Datatables\Configs;

use Zhyu\Datatables\AbstractDatatables;
use Zhyu\Datatables\DatatablesInterface;
use App\User;

class UserDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return User::class;
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

