<?php

namespace Osit\Webalitics;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Osit\Webalitics\Libraries\Webalitic;
use Osit\Webalitics\Libraries\GeoIP;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;

class Visilyzer
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //Load Config setting

        if(is_null(config('webalitic')) ) {
            die("Missing config. Ensure you have ran 'php artisan webalitics:process' or publish the config file " .
                        "by running 'php artisan vendor:publish --tag=webalitics-config'");
        }
        $website_settings = config('webalitic.website');
        $geoip_settings = config('webalitic.geoip');
        //Load Libraries
        $ip_country = new GeoIP($geoip_settings["clientId"], $geoip_settings["secret"]);
        $analytics = new Webalitic();
        //set Time Zone
        date_default_timezone_set($website_settings["time_zone"]);
        //check route
        $route = $request->route()->getName();
        //setting user IP address
        if (isset($_SERVER["HTTP_X_REAL_IP"]) && $_SERVER["HTTP_X_REAL_IP"] != "") {
            $user_ip = $_SERVER["HTTP_X_REAL_IP"];
        } else {
            $user_ip = $_SERVER["REMOTE_ADDR"];
        }
        //load ip database
        $ip_visits = DB::table('webalitic')
            ->where('ip', '!=', $user_ip)
            ->where('created_at', '>=', date("Y-m-d 00:00:00"))
            ->count();
        //get referrer
        $referrer = (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "");
        $referrer = str_replace('&', '&amp;', $referrer);

        if
        (
            $ip_visits == 0
            &&
            $analytics->monitor_route($route)
            &&
            !$analytics->is_spam($referrer)
            &&
            !$analytics->is_same_referral($referrer)
            &&
            !$analytics->exclude_custom_config($referrer)
            &&
            isset($_SERVER["HTTP_USER_AGENT"])
        ) {

            $day_visits = DB::table('webvisits')
                ->where('date', '=', date("Y-m-d"))
                ->count();

            $geoip = $ip_country->GeoLiteCity($user_ip);
            //get country
            if (array_key_exists("error", $geoip)) {
                $country = "UnKnown";
            } else {
                if (array_key_exists("country", $geoip)) {
                    $country = $geoip['country']['names']['en'];
                } else {
                    $country = $geoip['registered_country']['names']['en'];
                }
            }
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            $browser_name = 'Unknown';
            $os = 'Unknown';
            $version = "";
            $is_bot = "0";
            $ub = "Unknown";

            if (preg_match('/BotLink|bingbot|AdsBot-Google|AhrefsBot|ahoy|AlkalineBOT|anthill|appie|arale|araneo|AraybOt|ariadne|arks|ATN_Worldwide|Atomz|bbot|Bjaaland|Ukonline|borg\-bot\/0\.9|boxseabot|bspider|calif|CensysInspect|christcrawler|CMC\/0\.01|combine|confuzzledbot|CoolBot|cosmos|Internet Cruiser Robot|cusco|cyberspyder|cydralspider|desertrealm, desert realm|digger|DIIbot|grabber|domainsproject|downloadexpress|DragonBot|dwcp|ecollector|ebiness|elfinbot|esculapio|esther|facebook|fastcrawler|FDSE|FELIX IDE|ESI|fido|H�m�h�kki|KIT\-Fireball|fouineur|Freecrawl|gammaSpider|gazz|gcreep|Go-http-client|golem|googlebot|griffon|Gromit|gulliver|gulper|hambot|havIndex|hotwired|htdig|iajabot|INGRID\/0\.1|Informant|InfoSpiders|inspectorwww|irobot|Iron33|JBot|jcrawler|Teoma|Jeeves|jobo|image\.kapsi\.net|KDD\-Explorer|ko_yappo_robot|label\-grabber|larbin|legs|Linkidator|linkwalker|Lockon|logo_gif_crawler|marvin|mattie|mediafox|MerzScope|NEC\-MeshExplorer|MindCrawler|udmsearch|moget|Motor|msnbot|muncher|muninn|MuscatFerret|MwdSearch|sharp\-info\-agent|WebMechanic|Neevabot|NetScoop|newscan\-online|ObjectsSearch|Occam|Orbsearch\/1\.0|packrat|pageboy|ParaSite|patric|pegasus|perlcrawler|phpdig|piltdownman|Pimptrain|pjspider|PlumtreeWebAccessor|PortalBSpider|psbot|Getterrobo\-Plus|Raven|RHCS|RixBot|roadrunner|Robbie|robi|RoboCrawl|robofox|Scooter|Search\-AU|searchprocess|Senrigan|Shagseeker|sift|SimBot|Site Valet|skymob|SLCrawler\/2\.0|slurp|ESI|snooper|solbot|speedy|spider_monkey|SpiderBot\/1\.0|spiderline|nil|suke|http:\/\/www\.sygol\.com|Sogou|tach_bw|TechBOT|templeton|titin|topiclink|UdmSearch|urlck|Valkyrie libwww\-perl|verticrawl|Victoria|void\-bot|Voyager|VWbot_K|crawlpaper|wapspider|WebBandit\/1\.0|webcatcher|T\-H\-U\-N\-D\-E\-R\-S\-T\-O\-N\-E|WebMoose|webquest|webreaper|webs|webspider|WebWalker|wget|winona|whowhere|wlm|WOLP|WWWC|none|XGET|ZoominfoBot|Nederland\.zoek|AISearchBot|woriobot|NetSeer|Nutch|YandexBot|YandexMobileBot|SemrushBot|FatBot|MJ12bot|DotBot|AddThis|baiduspider|SeznamBot|mod_pagespeed|CCBot|openstat.ru\/Bot|m2e/i', $u_agent)) {
                $is_bot = '1';
                $os = 'Crawler/Bot';
                $browser_name = 'Crawler/Bot';
            }
            if (preg_match('/linux/i', $u_agent)) {
                $os = 'Linux';
            }
            if (preg_match('/iphone/i', $u_agent)) {
                $os = 'iPhone (iOS)';
            }
            if (preg_match('/ipage/i', $u_agent)) {
                $os = 'iPad (iOS)';
            }
            if (preg_match('/ipad/i', $u_agent)) {
                $os = 'iPad (iOS)';
            }
            if (preg_match('/android/i', $u_agent)) {
                $os = 'Android';
            }
            if (preg_match('/blackberry/i', $u_agent)) {
                $os = 'Blackberry';
            }
            if (preg_match('/macintosh/i', $u_agent) && preg_match('/mac os x/i', $u_agent)) {
                $os = 'Apple Mac';
            }
            if (preg_match('/windows phone/i', $u_agent)) {
                $os = 'Windows Phone';
            }
            if (preg_match('/windows|win32/i', $u_agent)) {
                $os = 'Windows';
            }
            if (preg_match('/windows|win64/i', $u_agent)) {
                $os = 'Windows';
            }

            if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                $browser_name = 'Internet Explorer';
                $ub = "MSIE";
            } elseif (preg_match('/SamsungBrowser/i', $u_agent)) {
                $browser_name = 'Samsung Browser';
                $ub = "Edge 11";
            } elseif (preg_match('/Trident/i', $u_agent)) {
                $browser_name = 'Microsoft Edge';
                $ub = "Edge 11";
            } elseif (preg_match('/Firefox/i', $u_agent)) {
                $browser_name = 'Mozilla Firefox';
                $ub = "Firefox";
            } elseif (preg_match('/Chrome/i', $u_agent)) {
                $browser_name = 'Google Chrome';
                $ub = "Chrome";
            } elseif (preg_match('/Safari/i', $u_agent)) {
                $browser_name = 'Apple Safari';
                $ub = "Safari";
            } elseif (preg_match('/Opera/i', $u_agent)) {
                $browser_name = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Netscape/i', $u_agent)) {
                $browser_name = 'Netscape';
                $ub = "Netscape";
            }

            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                $version = "Unknown";
            } else {
                $i = count($matches['browser']);
                if ($i != 1) {

                    if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                        $version = $matches['version'][0];
                    } else {
                        $version = "Unknown";
                    }
                } else {
                    $version = $matches['version'][0];
                }

                if ($version == null) $version = "Unknown";
            }


            //log as route visited
            //log info of enquiry
            $data = [
                "time" => time(),
                "ip" => $user_ip,
                "country" => $country,
                "page" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                "referer" => $referrer,
                "browser" => $browser_name,
                "version" => $version,
                "os" => $os,
                "is_bot" => $is_bot,
                "u_agent" => $u_agent,
                "m" => (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|CensysInspect|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $u_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($u_agent, 0, 4)) ? "1" : "0"),
                "geoip" => json_encode($geoip)
            ];
            //insert into table
            DB::table("webalitic")->insert($data);
            //log as a visit
            if ($day_visits == 0) {
                $data = array(
                    "date" => date("Y-m-d"),
                    "visits" => 1
                );
                DB::table("webvisits")->insert($data);
            } else {
                $get_visits = DB::table("webvisits")->where("date", "=", date("Y-m-d"))->get()->toArray();
                $visits = $get_visits[0]->visits;
                $data = array(
                    "visits" => $visits + 1
                );
                DB::table("webvisits")->where("date", "=", date("Y-m-d"))->update($data);
            }
        }

        return $response;
    }
}
