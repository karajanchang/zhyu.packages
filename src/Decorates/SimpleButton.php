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
	use TraitDecorate;
	
    public function __toString()
    {
        return '<a href="'.$this->renderUrl().'" class="'.$this->renderCss().'" data-toggle="tooltip" data-original-title="'.$this->getText().'"><i class="'.$this->getIcss().'" '.$this->renderAttribute().'></i></a>';
	    //return '<a href="'.route($this->route, $this->route_params).'" class="btn btn-info btn-circle btn-sm m-l-5" data-toggle="tooltip" data-original-title="修改"><i class="ti-pencil-alt"></i></a>';
    }
}
