<?php

namespace Zhyu\Datatables;

use App\Tool\EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbstractDatatables
{
    private $tableCreate;
    private $jsCreate;
    private $qa = null;

    protected $values = [];
    /**
     * @var EloquentBuilder
     */
    private $eloquentBuilder;

    public function __construct(TableCreate $tableCreate, JsCreate $jsCreate, EloquentBuilder $eloquentBuilder)
    {
        $this->tableCreate = $tableCreate;
        $this->jsCreate = $jsCreate;

        $this->eloquentBuilder = $eloquentBuilder;
    }

    public function table()
    {
        $config = $this->config();
        return $this->tableCreate->init($config);
    }

    public function js($varName = 'table')
    {
        $config = $this->config();
        //echo '<pre>';
        //print_R($config);
        return $this->jsCreate->init($config, $varName);

    }

    public function ajax()
    {
        $config = $this->config();
        $columns = $config['cols'];

        $model = new $config['model'];

        //$totalData = 0;
        //$totalFiltered = 0;

        $limit = request()->input('length');
        $limit = is_null($limit) ? 50 : $limit;
        $limit = (int)$limit;

        $start = request()->input('start');
        $start = is_null($start) ? 0 : $start;
        $start = (int)$start;
        //$this->qa = $model::select($this->getDbCols());
        //echo $config['model'];
        $this->qa = $this->eloquentBuilder->select($config['model'], $this->getDbCols());
        Log::info('limit: ' . $limit . ', start: ' . $start);

        $orderby = null;
        if (request()->has('order.0.column')) {
            $orderbyCols = $this->getOrderbyCols();
            //$orderby = $columns[$this->request->input('order.0.column')];
            if (isset($orderbyCols[request()->input('order.0.column')])) {
                $orderby = $orderbyCols[request()->input('order.0.column')];
            }
        }
        Log::alert(request()->input('order'));

        $dir = request()->input('order.0.dir');

        $no_run_keys = ['id', 'css', 'model', 'searchable_cols', 'no_orderable_cols', 'ajax', 'cols_display', 'cols'];
        foreach($config as $act => $params){
            if(is_array($params) && count($params) && !in_array($act, $no_run_keys)){
                foreach($params as $param) {
                    call_user_func_array(array($this->qa, $act), $param);
                }
            }
        }
        //dd($this->qa->toSql());
        $totalData = $this->qa->count($model->getTable() . '.id');
        Log::info('qa count: ' . $totalData);
        $totalFiltered = $totalData;

        if (empty(request()->input('search.value'))) {
            $this->qa->offset($start)->limit($limit);
            if (!is_null($orderby)) {
                $this->qa->orderBy($orderby, $dir);
            }
            Log::info($this->qa->toSql());

            $posts = $this->qa->get();
        } else {
            $search = request()->input('search.value');
            $this->qa->where($model->getTable() . '.id', 'LIKE binary', '%{$search}%');
            $searchable_cols = $config['searchable_cols'];
            if (count($searchable_cols)) {
                foreach ($searchable_cols as $col) {
                    if (isset($columns[$col])) {
                        $this->qa->orWhere($columns[$col], 'LIKE binary', "%{$search}%");
                    } else {
                        $this->qa->orWhere($col, 'LIKE binary', "%{$search}%");
                    }
                }
            }
            $totalFiltered = $this->qa->count();
            Log::info('totalFiltered: ' . $totalFiltered);

            $this->qa->offset($start)->limit($limit);
            if (!is_null($orderby)) {
                $this->qa->orderBy($orderby, $dir);
            }
//            $this->qa->select($this->getDbCols());

            Log::info('totalFilteredSql: ' . str_replace('?', "%{$search}%", $this->qa->toSql()));
            $posts = $this->qa->get();
        }

        $data = array();
        if (!empty($posts)) {
            $config = $this->config();
            $cols_display = $config['cols_display'];

            $data = $posts->map(function ($row, $key) use ($columns, $cols_display) {
                $ro = [];
                foreach ($columns as $key => $col) {
                    //echo $key.'=='.$col."\n";
                    if (is_int($key)) {
                        $ro[$col] = $row->$col;
                    } else {
                        $ro[$key] = $row->$key;
                    }
                }
                foreach ($cols_display as $key => $col) {
                    if (isset($col['callback'])) {
                        $pars = [];
                        if (isset($col['params']) && count($col['params'])) {
                            foreach ($col['params'] as $pp) {
                                $pars[] = $row->$pp;
                            }
                        }
                        //dd($pars);
                        $ro[$key] = call_user_func($col['callback'], $pars);
                    }
                }
                return $ro;
            });
        }

        $json_data = array(
            "draw" => intval(request()->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return json_encode($json_data);
    }

    private function getDbCols(){
	    $config = $this->config();
	    $rcol = [];
	    if(isset($config['cols']) && count($config['cols'])){
	        foreach($config['cols'] as $key => $col){
	        	if(is_int($key)) {
			        $rcol[] = $col;
		        }else{
	        		$rcol[] = DB::raw("$col as $key");
		        }
	        }
	    }
	    //dd($rcol);
	    return $rcol;
    }

    private function getOrderbyCols(){
        $config = $this->config();
        $rcol = [];
        if(isset($config['cols']) && count($config['cols'])){
            foreach($config['cols'] as $key => $col){
            	if(is_int($key)) {
			        $rcol[] = $col;
		        }else{
	        		$rcol[] = $key;
		        }
            }
        }
        return $rcol;
    }


}


