<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-11
 * Time: 11:51
 */

namespace Zhyu\Report;


use Zhyu\Facades\CsvReport;
use Zhyu\Facades\PdfReport;

abstract class ReportAbstract
{
    public function fire($type='pdf', $filename = null){
        if($type=='csv') {
            $ob = CsvReport::of($this->title(), $this->meta(), $this->query(), $this->columns());
        }elseif($type=='pdf'){
            $ob = PdfReport::of($this->title(), $this->meta(), $this->query(), $this->columns());
        }
        $name = is_null($filename) ? date('Y-m-d') : $filename;
        return $ob->download($name);
    }
}