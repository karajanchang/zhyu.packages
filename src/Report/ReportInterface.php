<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-11
 * Time: 11:22
 */

namespace Zhyu\Report;


interface ReportInterface
{
    public function title();
    public function meta();
    public function columns();
    public function query();
}