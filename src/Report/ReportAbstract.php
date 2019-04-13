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
use Zhyu\Repositories\Criterias\Common\OrderByCustom;

abstract class ReportAbstract
{
	protected $orderby_col;
    private $limit = 0;
	
	/**
	 * @return mixed
	 */
	public function getOrderbyCol() {
		return $this->orderby_col;
	}
	/**
	 * @param mixed $orderby_col
	 */
	public function setOrderbyCol($orderby_col) {
		$this->orderby_col = $orderby_col;
		
		return $this;
	}

		
    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }



    public function fire($type='pdf', $filename = null){
	    $this->orderby();
    	$this->limit();
        if($type=='csv') {
            $ob = CsvReport::of($this->title(), $this->meta(), $this->query(), $this->columns());
        }elseif($type=='pdf'){
            $ob = PdfReport::of($this->title(), $this->meta(), $this->query(), $this->columns());
        }
        $limit = $this->getLimit();
        if($limit>0){
            $ob->limit($limit);
        }

        $name = is_null($filename) ? date('Y-m-d') : $filename;
        return $ob->download($name);
    }
	
	protected function orderby(){
		$this->orderby = isset($this->params['orderby']) ? $this->params['orderby'] : 'desc';
		$criteria = new OrderByCustom($this->orderby_col, $this->orderby);
		$this->repository->pushCriteria($criteria);
	}
	protected function limit(){
		if(isset($this->params['limit'])){
			$this->setLimit($this->params['limit']);
		}
	}
}