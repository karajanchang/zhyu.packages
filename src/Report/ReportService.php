<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-11
 * Time: 11:44
 */

namespace Zhyu\Report;


class ReportService
{
    /**
     * @var ReportInterface
     */
    private $report;

    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
        \Debugbar::disable();
    }

    public function fire($type='pdf', $filename = null){
        return $this->report->fire($type, $filename);
    }

}