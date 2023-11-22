<?php

namespace Osit\Webalitics\Libraries;

class GeoIP{
    //PROTECTED COMMON VARIABLES
    protected $countryURL = 'https://geolite.info/geoip/v2.1/country/'; //LIVE SERVICES
    protected $cityURL = 'https://geolite.info/geoip/v2.1/city/'; //LIVE SERVICES
    protected $clientId = "";
    protected $secret = "";

    public function __construct($clientId,$secret)
    {
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    //Call GeoIP Insights
    public function GeoLiteCountry($ipAddress){
        $url = $this->countryURL.$ipAddress;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Accept: application/vnd.maxmind.com-insights+json; charset=UTF-8; version=2.1',
            'Authorization: Basic '.base64_encode($this->clientId.':'.$this->secret)
        ));
        $output = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return json_decode($output, true);

        //return $info ;

    }

    //Call GeoIP Insights
    public function GeoLiteCity($ipAddress){
        $url = $this->cityURL.$ipAddress;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Accept: application/vnd.maxmind.com-insights+json; charset=UTF-8; version=2.1',
            'Authorization: Basic '.base64_encode($this->clientId.':'.$this->secret)
        ));
        $output = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return ["output" => json_decode($output, true), "info" => $info];
    }

}
