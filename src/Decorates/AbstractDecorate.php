<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-07
 * Time: 10:36
 */

namespace Zhyu\Decorates;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractDecorate
{
    public $data;
    public $route;
    public $route_params = [];
    public $css;

    public $icss;

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

    public function _(){
        return $this->__toString();
    }


}