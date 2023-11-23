<?php

namespace Osit\Webalitics\Libraries;

/**
 * Webalitic - main class
 *
 * Webalitic
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
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

    /**
     * IS $str a search engine?
     *
     * @param $str
     * @return bool
     */
    function is_search_engine($str): bool
    {
        $arr_search_engines = array("google", "yandex", "yahoo", "bing", "baidu", "aol", "duckduckgo");

        foreach ($arr_search_engines as $search_engine) {
            if (stripos($str, $search_engine . ".") !== false) return true;
        }
        return false;
    }


    /**
     * Get search engine type.
     *
     * @param $str
     * @return string
     */
    function search_engine_type($str): string
    {
        $arr_search_engines = array("google", "yandex", "yahoo", "bing", "baidu", "aol", "duckduckgo");

        if (trim($str) == "") return "";

        foreach ($arr_search_engines as $search_engine) {
            if (stripos($str, $search_engine . ".") !== false) return $search_engine;
        }
        return "";
    }

    /**
     * Is $str spam?
     *
     * @param $str
     * @return bool
     */
    function is_spam($str): bool
    {
        $arr_spam_referrrals = array("fuzzer", "cjb.net");

        foreach ($arr_spam_referrrals as $spam_referral) {
            if (stripos($str, $spam_referral) !== false) return true;
        }
        return false;
    }

    /**
     * Is $str the same domain?
     *
     * @param $str
     * @return bool
     */
    function is_same_domain($str): bool
    {
        if (stripos($str, $this->get_domain()) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is $str the same referral?
     *
     * @param $str
     * @return bool
     */
    function is_same_referral($str): bool
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

    /**
     * Is $route monitored?
     *
     * @param $route
     * @return bool
     */
    function monitor_route($route): bool
    {
        if(in_array("all",$this->website_settings["routes"])) {
            return true;
        }elseif(in_array($route,$this->website_settings["routes"])) {
            return true;
        }else {
           return false;
        }
    }

    /**
     * Get the engine name.
     *
     * @param $str
     * @return array|string
     */
    function get_engine_name($str): array|string
    {
        $parse = parse_url($str);
        return str_ireplace('www.', '', $parse['host']);

    }

    /**
     * Get the domain.
     *
     * @return mixed
     */
    function get_domain()
    {
        if (trim($_SERVER['SERVER_NAME']) != "") {
            return $_SERVER['SERVER_NAME'];
        } else {
            return $_SERVER['HTTP_HOST'];
        }


    }

    /**
     * Exclude custom config.
     *
     * @param $referral
     * @return bool
     */
    function exclude_custom_config($referral = ""): bool
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
