<?php

namespace Osit\Webalitics\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Osit\Webalitics\Models\WebaliticUser;

class WebaliticController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {

    }
    public function adminWebalitic(Request $request)
    {
        /**
         * If there is no entry for a User with user_name = {env("WEBALITIC_USER_NAME")} in {webalitic_user} table,
         * create the record.
         */
        WebaliticUser::upsert(
            [
                "id" => 1,
                "user_name" => env("WEBALITICS_USER_NAME") ?? "unknown_user",
                "website_name" => env("WEBALITICS_WEBSITE_NAME") ?? "unknown_website"
            ],
            ["id", "user_name", "website_name"],
            ["user_name", "website_name"]
        );

        $month = date('n');
        if (($request->post('startdate')) && ($request->post('enddate'))) {
            $startDate = $request->post('startdate') . " 00:00:00";
            $endDate = $request->post('enddate') . " 23:59:59";
        } else {
            $startDate = date("Y-m-d 00:00:00");
            $endDate = date("Y-m-d 23:59:59");
        }

        $osSeries = [];
        $osLabels = [];
        $os_count = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->select('os', DB::raw('count(*) as total'))
            ->groupBy('os')
            ->get();
        foreach ($os_count as $os) {
            array_push($osSeries, $os->total);
            array_push($osLabels, $os->os);
        }
        $bSeries = [];
        $bLabels = [];
        $browser = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->get();
        foreach ($browser as $b) {
            array_push($bSeries, $b->total);
            array_push($bLabels, $b->browser);
        }
        $cSeries = [];
        $cLabels = [];
        $country = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->get();
        foreach ($country as $c) {
            array_push($cSeries, $c->total);
            array_push($cLabels, $c->country);
        }
        $desktop = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->where("m", "=", "0")
            ->count();
        $mobile = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->where("m", "=", "1")
            ->count();
        $today = DB::table('webalitic')
            ->where("created_at", ">=", date("Y-m-d 00:00:00"))
            ->where("created_at", "<=", date("Y-m-d 23:59:59"))
            ->count();
        $transactions = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->get()
            ->toArray();
        $is_bot = DB::table('webalitic')
            ->where("created_at", ">=", $startDate)
            ->where("created_at", "<=", $endDate)
            ->where("is_bot", "=", "1")
            ->count();
        $month_transactions = DB::table('webalitic')
            ->where("created_at", ">=", date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, date('Y'))))
//            ->where("created_at", "<=", date('Y-m-d H:i:s', mktime(23, 59, 59, $month + 1, 0, date('Y'))))
            ->where("created_at", "<=", date('Y-m-d H:i:s', strtotime( mktime(23, 59, 59, $month, 0, date('Y')) . "+1 month")))
            ->count();


        $webalitic_user = DB::table('webalitic_user')
                            ->where("user_name",
                                    "=",
                                    env("WEBALITICS_USER_NAME") ?? "unknown_user")
                            ->get()
                            ->toArray();

        $data = array(
            "oslabels" => $osLabels,
            "osseries" => $osSeries,
            "blabels" => $bLabels,
            "bseries" => $bSeries,
            "clabels" => $cLabels,
            "cseries" => $cSeries,
            "is_bot" => $is_bot,
            "desktop" => $desktop,
            "mobile" => $mobile,
            "today" => $today,
            "month_transactions" => $month_transactions,
            "transactions" => $transactions,
            "webalitic_user" => $webalitic_user
        );
        return view('webalitics::webalitic', $data);
    }

    public function adminWebaliticProfile(Request $request)
    {
        $id = $request->post('id');
        $visitor_info = DB::table('webalitic')
            ->where("id", "=", $id)
            ->get();
        $data = array(
            "visitor_info" => $visitor_info[0]
        );
        return view('webalitics::webalitic_profile', $data);
    }
}
