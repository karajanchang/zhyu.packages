<?php

namespace Zhyu\Decorates;

trait TraitDecorate {
	
	public function renderUrl(){
		$route = $this->getRoute();
		if(!$route){
			return ;
		}
		return route($route, $this->getRouteParams());
	}
	public function renderCss(){
		return $this->renderCssByCss($this->getCss());
	}
	public function renderIcss(){
		return $this->renderCssByCss($this->getCss());
	}
	private function renderCssByCss($tcss){
		$css = '';
		if(is_string($tcss)){
			$css = $tcss;
		}
		if(is_array($tcss)){
			$css = join(' ', $tcss);
		}
		return $css;
	}
	
	public function renderAttribute(){
		$attrArray = [];
		$attributes = $this->getAttributes();
		if(count($attributes)){
			foreach($attributes as $attribute){
				if(is_array($attribute)){
					foreach($attribute as $key => $val){
						$attrArray[] = $key.'="'.$val.'"';
					}
				}else{
					$attrArray[] = $attribute;
				}
			}
			return join(' ', $attrArray);
		}
		return '';
	}
}