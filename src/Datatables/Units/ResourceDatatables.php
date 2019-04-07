<?php

namespace Zhyu\Datatables\Units;

use Zhyu\Datatables\AbstractDatatables;
use Zhyu\Datatables\DatatablesInterface;
use Zhyu\Model\Resource;

class ResourceDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return Resource::class;
    }

    /**
     * Set custom act.
     *
     * @return string
     */
    public function act(){
        return 'ajax';
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
                'orderby' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'icon_css' => [
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

