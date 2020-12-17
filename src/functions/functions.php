<?php
//--兩點的直線距離
if (!function_exists('DistanceTwoPointByLine')) {
    function  DistanceTwoPointByLine(float $lat1, float $lon1, float $lat2, float $lon2){
        //将角度转为狐度
        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLng1=deg2rad($lon1);
        $radLat2=deg2rad($lat2);
        $radLng2=deg2rad($lon2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;

        return $s;
    }
}
