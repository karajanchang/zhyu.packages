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
    private $ch = null;
    private $url='';
    private $auth = [];

    public function __construct($url, array $auth = []){
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

    public function json($postData, $is_assoc = true){

        $this->init();

        curl_setopt_array($this->ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => $this->header($this->auth),
            CURLOPT_POSTFIELDS => json_encode($postData),
        ));

        $data = $this->output();
        if($data===FALSE){
            return curl_error($this->ch);
        }

        $responseData = json_decode($data, $is_assoc);

        return $responseData;
    }

    public function post($postData){
        $this->init();

        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');

        $data = $this->output();

        return $data;
    }

    public function get() {
        $this->init();

        curl_setopt($this->ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);


        $data = $this->output();

        return $data;
    }

    private function init(){
        $this->ch = curl_init($this->url);
    }

    private function output(){
        $data = curl_exec($this->ch);
        curl_close($this->ch);

        return $data;
    }
}