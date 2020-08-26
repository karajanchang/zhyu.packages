<?php


namespace Zhyu\Tools;


class VersionCompare
{
    private $version;
    private $version_ask;

    public function __construct($version, $version_ask)
    {
        $this->version = $version;
        $this->version_ask = $version_ask;
    }

    public function isOutOfDate() : bool{
        $app_mini_vers = explode('.', $this->version_ask);
        $AppVers = explode('.', $this->version);

        foreach($app_mini_vers as $key => $mini_ver){
            $mini_ver = (int) $mini_ver;
            $appVer = (int) $AppVers[$key];
            if($mini_ver == $appVer){
                continue;
            }

            //--版本比較舊
            if($mini_ver > $appVer){

                return true;
            }

            //--版本比較新
            if($mini_ver < $appVer){

                return false;
            }
        }

        return false;
    }

}