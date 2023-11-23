<?php

$monitoredRoutes = [];

return [

    /*
    |--------------------------------------------------------------------------
    | Visitor Analytics
    |--------------------------------------------------------------------------
    |
    | Visit Analytics is an advanced web counter , which can be used to track the web visits and see different
    | reports and analytics for the daily visits, traffic sources, web referrals, search engine visits,
    | visitor countries and others.
    |
    */

    "website" => [
        //If route array has the all routes then all routes will be monitored, else only add routes you wish to monitor.
        "routes" => ["","about-us","contact-us","login"],
        "date_format" => "Y-m-d",
        "hour_format" => "H:i:s",
        "time_zone" => "Africa/Johannesburg",
        "domain_name" => "localhost",
    ],

    "geoip" => [
        "clientId" => "",
        "secret" => ""
    ]

];
