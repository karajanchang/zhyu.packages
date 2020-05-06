<?php
//---給表單用，檢查此表單元素是否有在query裡
if (!function_exists('QueryIfExist')) {
    function QueryIfExist(array $query = null, string $col, string $table = null, string $callback = null){
        if(is_null($query)) return false;

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
                    $value = QueryColValue($query, $col, $table);

                    return call_user_func($callback, $value);
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
    function QueryIfArrayEual($query = null, $column, $callback){
        if(is_null($query)) return false;

        if(is_array($query[$column])){
            $key = count($query[$column])-1;

            return RemoveUnwantTagsFromValue($query[$column][$key]) == $callback;
        }

        return RemoveUnwantTagsFromValue($query[$column]) == $callback;
    }
}

if (!function_exists('QueryColValue')) {
    function QueryColValue(array $query = null, string $col, string $table = null)
    {
        if(is_null($query)) return '';

        if (empty($col)) {

            throw new \Exception('Please enter parameter: col');
        }
        if (isset($query[$col])) {

            return RemoveUnwantTagsFromValue($query[$col]);
        }

        $column = $table . '.' . $col;
        if (isset($query[$column])) {

            $b = QueryColValueArray($query, $column);

            return $b;
        }

        return;
    }
}
if (!function_exists('RemoveUnwantTagsFromValue')) {
    function RemoveUnwantTagsFromValue($vals){
        if(!is_array($vals)){

            return $vals;
        }
        $array = [];
        $unwant_tags = ['>=', '<=', '=', '>', '<'];
        foreach($vals as $val){
            if(!in_array($val, $unwant_tags)){
                array_push($array, $val);
            }
        }

        return $array;
    }
}

if (!function_exists('QueryColValueArray')) {
    function QueryColValueArray($query = null, $column)
    {
        if(is_null($query)) return '';

        if (is_array($query[$column])) {
            $key = count($query[$column]) - 1;

            return $query[$column][$key];
        }

        return $query[$column];
    }
}

if (!function_exists('QueryIsArray')) {
    function QueryIsArray($val){

        return is_array($val) && count($val) >= 2 ? true : false;
    }
}
