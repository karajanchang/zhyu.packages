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

class NormalButton extends AbstractDecorate implements InterfaceDecorate
{
    use TraitDecorate;

    public function __toString()
    {
        try {
            return '<a href="' . $this->renderUrl() . '" class="' . $this->renderCss() . '" style="margin:10px" ' . $this->renderTitle() . '><i class="' . $this->getIcss() . '" ' . $this->renderAttribute() . '></i>' . $this->getText() . '</a>';
        }catch (\Exception $e){
            $msg = env('APP_DEBUG')===true ? $e->getMessage() : '';
            return $msg;
        }
    }
}
