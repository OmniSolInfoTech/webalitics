<?php

namespace Osit\Webalitics\Libraries;

/**
 * GeoIP - main class
 *
 * GeoIP
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
class GeoIP
{
    //PROTECTED COMMON VARIABLES
    protected string $countryURL = 'https://geolite.info/geoip/v2.1/country/'; //LIVE SERVICES
    protected string $cityURL = 'https://geolite.info/geoip/v2.1/city/'; //LIVE SERVICES
    protected string $clientId = "";
    protected string $secret = "";

    public function __construct($clientId,$secret)
    {
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    /*
     * Call GeoIP Insights
     *
     * @param $ipAddress
     * @return mixed
     */
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
    }

    /**
     * Call GeoIP Insights
     *
     * @param $ipAddress
     * @return array
     */
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
