<?php

namespace Zhyu\Report;

use Zhyu\Report\Origin\OriginCsv;

abstract class ReportOriginAbstract implements ReportInterface
{
    # Create For Interface
    public function meta(){}
    public function title(){}
    public function query(){}
    public function columns(){}

    abstract public function name();
    abstract public function data();

    public function fire($type='csv')
    {
        $columns = $this->columns();
        $columns = ($columns) ? $columns : [];

        $data = $this->data();
        $data = (is_array($data)) ? $data : [];

        if ($columns) {
            array_unshift($data, $columns);
        }

        $fileName = $this->name();
        $fileName = ($fileName) ? $fileName : 'report';

        OriginCsv::export($fileName, $data);
    }
}