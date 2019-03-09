<?php

namespace Zhyu\Decorates;

class FactoryDecorate {
	public static function getDecorate($which) {
		if(!class_exists($which)){
			throw new \Exception('This class can not callable: '.$which);
		}
		app()->bind(InterfaceDecorate::class, function($app) use($which){
			return new $which;
		});
		return app()->make(InterfaceDecorate::class);
	}
}