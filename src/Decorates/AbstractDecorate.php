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
    private $route;
    private $route_params = [];
    private $css;

    private $icss;
    
    private $attributes = [];
    
    private $text;
	
	/**
	 * AbstractDecorate constructor.
	 * @param $data
	 * @param $route
	 * @param array $route_params
	 * @param $css
	 * @param $icss
	 * @param array $attributes
	 * @param $text
	 */
	public function __construct($data = null, $route = null, array $route_params = [], $css = null, $icss = null, array $attributes = [], $text = null) {
		$this->data = $data;
		$this->route = $route;
		$this->route_params = $route_params;
		$this->css = $css;
		$this->icss = $icss;
		$this->attributes = $attributes;
		$this->text = $text;
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
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function getRouteParams()
    {
        return $this->route_params;
    }

    /**
     * @param array $route_params
     */
    public function setRouteParams($route_params)
    {
        $this->route_params = $route_params;
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
		if( $tran_text != 'zhyu::common.logouta'){
			$this->text = $tran_text;
			return $this;
		}
		$this->text = $text;
		return $this;
	}
	
	
    

    public function _(){
        return $this->__toString();
    }


}