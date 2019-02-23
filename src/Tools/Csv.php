<?php

namespace Zhyu\Repositories\Tools;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;

class Csv
{
	public static function output($title, array $columns, Collection $collections){
		$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename='".trim($title).".csv'",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0",
		);
		$callback = function() use ($title, $collections, $columns)
		{
			$file = fopen('php://output', 'w');
			fputs($file, "\xEF\xBB\xBF");
			fputcsv($file, [$title]);
			fputcsv($file, $columns);
			foreach($collections as $co) {
				$data = [];
				foreach($columns as $key => $va){
					if(is_object($co)) {
						$data[$key] = $co->$key;
					}elseif(is_Array($co)){
						$data[$key] = $co[$key];
					}else{
						$data[$key] = '';
					}
				}
				fputcsv($file, $data);
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
	}
}