<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-07
 * Time: 10:36
 */

namespace Zhyu\Decorates;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

abstract class AbstractDecorate
{
    private $data;
    private $link;
    private $css;

    private $icss;

    private $attributes = [];

    private $text;

    private $title;

    /**
     * AbstractDecorate constructor.
     * @param $data
     * @param $link
     * @param $css
     * @param $icss
     * @param array $attributes
     * @param $text
     * @param $title
     */
    public function __construct($data = null, $link = null, $css = null, $icss = null, $attributes = [], $text = null, $title = null) {
        $this->data = $data;
        $this->link = $link;
        $this->css = $css;
        $this->icss = $icss;
        $this->attributes = is_null($attributes) ? [] : $this->attributes;
        $this->setText($text);

        $this->title = $title;
    }

    public function renderUrl(){
        $rurl = $this->link;
        if(isset($this->url)){
            $rurl =  $this->url;
        }
        if(is_array($rurl)){
            $url = call_user_func_array('route', array_merge($rurl , [false]));
            return $url;
        }
        if(is_string($rurl)){
            $url = $rurl;
            return $url;
        }
        return 'javascript:;';
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(Model $data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param mixed $css
     */
    public function setCss($css)
    {
        $this->css = $css;
    }

    /**
     * @return mixed
     */
    public function getIcss()
    {
        return $this->icss;
    }

    /**
     * @param mixed $icss
     */
    public function setIcss($icss)
    {
        $this->icss = $icss;
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes) {
        $this->attributes = $attributes;

        return $this;
    }

    public function pushAttributes($attribute){
        array_push($this->attributes, $attribute);
    }

    /**
     * @return mixed
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text) {
        $tran_text = trans('zhyu::common.'.$text);
        if( $tran_text != 'zhyu::common.'.$text){
            $this->text = $tran_text;
            return $this;
        }
        $this->text = $text;
        return $this;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return null
     */
    public function renderTitle()
    {
        if(strlen($this->title) > 0 ){
            return ' title="'.$this->getTitle().'" ';
        }
        return null;
    }



    public function _(){
        return $this->__toString();
    }


}