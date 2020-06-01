<?php

/**
 *  This class provides access to the Google API (PHP);
 */
class GoogleMaps{

  private $api_key = "AIzaSyAQSdzCESsae3JfhpTN8WEagdE1Zj4AA0A";

  function __construct(){
    // code...
  }

  function getDrivingDistance($origin, $destination){
    $key = $this->api_key;

    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&language=en&key=$key";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    //var_dump($response_a);
    //return $response;
    return array('distance' => $dist, 'time' => $time);
  }

}



?>
