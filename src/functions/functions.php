<?php
//---給表單用，檢查此表單元素是否有在query裡
if (!function_exists('QueryIfExist')) {
    function QueryIfExist(array $query, string $col, string $table = null, string $callback = null){
        $bool = 0;

        if(empty($col)){

            throw new \Exception('Please enter parameter: col');
        }
        if(isset($query[$col])){

            $bool = 1;
        }

        $column = $table.'.'.$col;
        if(isset($query[$column])){


            $bool = 2;
        }

        if($bool > 0){
            if(!is_null($callback)) {
                if(function_exists($callback)){

                    return call_user_func($callback, [$query, $column]);
                }

                if($bool==1){

                    return QueryIfArrayEual($query, $col, $callback);
                }
                if($bool==2) {

                    return QueryIfArrayEual($query, $column, $callback);
                }
            }

            return true;
        }

        return false;
    }
}

if (!function_exists('QueryIfArrayEual')) {
    function QueryIfArrayEual($query, $column, $callback){
        if(is_array($query[$column])){
            $key = count($query[$column])-1;

            return $query[$column][$key] == $callback;
        }

        return $query[$column] == $callback;
    }
}

function QueryColValue(array $query, string $col, string $table = null){
    if(empty($col)){

        throw new \Exception('Please enter parameter: col');
    }
    if(isset($query[$col])){

        return $query[$col];
    }

    $column = $table.'.'.$col;
    if(isset($query[$column])){

        $b = QueryColValueArray($query, $column);

        return $b;
    }

    return ;
}

function QueryColValueArray($query, $column){
    if(is_array($query[$column])){
        $key = count($query[$column])-1;

        return $query[$column][$key];
    }

    return $query[$column];
}
