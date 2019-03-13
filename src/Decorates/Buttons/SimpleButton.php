<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-06
 * Time: 11:04
 */

namespace Zhyu\Decorates\Buttons;

use Zhyu\Decorates\AbstractDecorate;
use Zhyu\Decorates\InterfaceDecorate;
use Zhyu\Decorates\TraitDecorate;

class SimpleButton extends AbstractDecorate implements InterfaceDecorate
{
	use TraitDecorate;
	
    public function __toString()
    {
        return '<a href="'.$this->renderUrl().'" class="'.$this->renderCss().'" data-toggle="tooltip" data-original-title="'.$this->getText().'" title="'.$this->getTitle().'"><i class="'.$this->getIcss().'" '.$this->renderAttribute().'></i></a>';
    }
}
