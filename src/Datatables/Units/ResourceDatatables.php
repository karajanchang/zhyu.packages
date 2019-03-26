<?php

namespace Zhyu\Datatables\Units;

use Zhyu\Datatables\AbstractDatatables;
use Zhyu\Datatables\DatatablesInterface;
use Zhyu\Model\Resource;

class ResourceDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return new Resource();
    }

    public function config(){
        return [
            'id' =>  'myTable',
            'css' =>  [ 'table', 'manage-u-table', 'table-striped', 'dataTable', 'nowrap' ],
            'searchable_cols' => [ 'name',  'route'],
            'no_orderable_cols' => [],
            'cols_display' => [
                'name' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'route' => [
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

