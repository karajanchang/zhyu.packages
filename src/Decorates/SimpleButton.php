<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-06
 * Time: 11:04
 */

namespace Zhyu\Decorates;


class SimpleButton extends AbstractDecorate implements InterfaceDecorate
{

    public function __toString()
    {
        return '<a href="'.route('logistics.edit', [ "id" => $this->id ]).'" class="btn btn-info btn-circle btn-sm m-l-5" data-toggle="tooltip" data-original-title="修改"><i class="ti-pencil-alt"></i></a>';
    }


}
