<?php

namespace Zhyu\Report\Origin;

use Illuminate\Support\Facades\Response;

Class OriginCsv
{
    public static function export($fileName, $data)
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName.csv",
            "Expires" => "0",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        ];

        $callback = function() use ($data)
        {
            $fp = fopen('php://output', 'w');
            fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            foreach($data as $raw) {
                if (is_array($raw)) {
                    fputcsv($fp, $raw);
                } else {
                    fwrite($fp, $raw);
                }
            }
            fclose($fp);
        };

        return response()->stream($callback, 200, $headers)->send();
    }
}