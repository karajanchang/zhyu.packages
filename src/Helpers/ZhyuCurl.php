<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-23
 * Time: 13:32
 */

namespace Zhyu\Helpers;


class ZhyuCurl
{
    private static $ch = null;
    private $url='';
    private $auth = [];
    private $method = null;

    public function __construct($url = '', array $auth = []){
        $this->url = $url;
        $this->auth = $auth;
    }

    private function header(array $auth = []){
        $header = [
            'Content-Type: application/json'
        ];
        if(count($auth)){
            $header = array_merge($header, $auth);
        }
        return $header;
    }

    public function url($url){
        $this->url = $url;
        return $this;
    }

    public function auth(array $auth){
        $this->auth = $auth;
        return $this;
    }

    public function method($method = 'post'){
        $ch = self::init($this->url);

        Switch(strtolower($method)){
            case 'patch':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;

            case 'put':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;

            case 'delete':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            default:
                curl_setopt($ch, CURLOPT_POST, true);
        }
        $this->method = 'post';

        return $this;
    }

    public function json($postData, $is_assoc = true){
        $ch = self::init($this->url);

        if(is_null($this->method)){
            $ch = $this->method();
        }
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header($this->auth));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');


        $data = $this->output();
        if($data===FALSE){
            return curl_error($this->ch);
        }

        $responseData = json_decode($data, $is_assoc);

        return $responseData;
    }

    public function post($postData){
        return $this->_post($postData, 'post');
    }

    public function put($postData){
        return $this->_post($postData, 'put');
    }

    public function patch($postData){
        return $this->_post($postData, 'patch');
    }

    public function delete($postData){
        return $this->_post($postData, 'delete');
    }

    public function _post($postData, $method){
        $ch = self::init($this->url);

        if(is_null($this->method)){
            $ch = $this->method($method);
        }
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');

        $data = $this->output();

        return $data;
    }

    public function get() {
        $ch = self::init($this->url);

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = $this->output();

        return $data;
    }

    private static function init($url){
        if(self::$ch===null) {
            self::$ch = curl_init($url);
        }
        return self::$ch;
    }

    private function output(){
        $data = curl_exec(self::$ch);
        curl_close(self::$ch);

        return $data;
    }
}