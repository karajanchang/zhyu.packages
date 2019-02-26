<?php

namespace Zhyu\Tools;

class Zip {
	public function getContent($file){
		if(!file_exists($file)){
			throw new \Exception("zip file dosn't exists");
		}
		$zip = zip_open($file);
		if($zip){
			while($entry = zip_read($zip)){
				//echo 'names: '.zip_entry_name($entry).'<br />';
				if(zip_entry_open($zip, $entry)){
					$content = zip_entry_read($entry, zip_entry_filesize($entry));
					return $content;
				}
			}
		}
	}
}