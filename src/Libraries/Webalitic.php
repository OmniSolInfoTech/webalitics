<?php

namespace Osit\Webalitics\Libraries;

class Webalitic
{

    //Load Config setting
    public $website_settings;
    public $traffic_settings;

    function __construct()
    {
        //Load Config setting
        $this->website_settings = config('webalitic.website');
        $this->traffic_settings = config('webalitic.traffic');
    }

    function is_search_engine($str)
    {
        $arr_search_engines = array("google", "yandex", "yahoo", "bing", "baidu", "aol", "duckduckgo");

        foreach ($arr_search_engines as $search_engine) {
            if (stripos($str, $search_engine . ".") !== false) return true;
        }
        return false;
    }


    function search_engine_type($str)
    {
        $arr_search_engines = array("google", "yandex", "yahoo", "bing", "baidu", "aol", "duckduckgo");

        if (trim($str) == "") return "";

        foreach ($arr_search_engines as $search_engine) {
            if (stripos($str, $search_engine . ".") !== false) return $search_engine;
        }
        return "";
    }

    function is_spam($str)
    {
        $arr_spam_referrrals = array("fuzzer", "cjb.net");

        foreach ($arr_spam_referrrals as $spam_referral) {
            if (stripos($str, $spam_referral) !== false) return true;
        }
        return false;
    }

    function is_same_domain($str)
    {
        if (stripos($str, $this->get_domain()) !== false) {
            return true;
        } else {
            return false;
        }
    }

    function is_same_referral($str)
    {
        global $_SERVER;


        if
        (
            preg_match('/https?:\/\/(www.)?(' . str_replace("www.", "", $_SERVER["HTTP_HOST"]) . '|' . str_replace("www.", "", $this->get_domain()) . ')\/?/', $str, $match)
        ) {
            return true;
        } else {
            return false;
        }
    }

    function monitor_route($route)
    {
        if(in_array("all",$this->website_settings["routes"])) {
            return true;
        }elseif(in_array($route,$this->website_settings["routes"])) {
            return true;
        }else {
           return false;
        }
    }

    function get_engine_name($str)
    {
        $parse = parse_url($str);
        return str_ireplace('www.', '', $parse['host']);

    }

    function get_domain()
    {
        if (trim($_SERVER['SERVER_NAME']) != "") {
            return $_SERVER['SERVER_NAME'];
        } else {
            return $_SERVER['HTTP_HOST'];
        }


    }

    function exclude_custom_config($referral = "")
    {

        if (isset($this->traffic_settings["direct_traffic"]) && $this->traffic_settings["direct_traffic"] == "0" && $referral == "") {
            return true;
        }

        if (isset($this->traffic_settings["exclude_referrals"]) && $this->traffic_settings["exclude_referrals"] == "0" && strpos($this->traffic_settings["exclude_referrals"], $referral) !== false) {
            return true;
        }

        return false;

    }

}

?>
