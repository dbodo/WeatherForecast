<?php
/**
 * Created by PhpStorm.
 * User: DomagojBodo
 * Date: 23.5.2018.
 * Time: 4:12
 */

include "connection.php";
$sCurloptUrl = 'http://api.openweathermap.org/data/2.5/forecast?q=Zagreb&mode=json&appid=5630f4dfb2c7d47e5b34dc623b30ffef';
$headers = [
    'Content-Type: application/json',
];
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HTTPGET => true,
    CURLOPT_SAFE_UPLOAD => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_URL => $sCurloptUrl
));
$oResponse = curl_exec($curl);
curl_close($curl);
$oResponse = json_decode($oResponse, true);
$iconUrl = "http://openweathermap.org/img/w/";
$oGrad = $oResponse['city'];


foreach ($oResponse['list'] as $oForecast) {
    $oJson[] = new City(
        $oGrad['id'],
        $oGrad['name'],
        $oGrad['country'],
        $oGrad['coord']['lat'],
        $oGrad['coord']['lon'],
        $oForecast['dt_txt'],
        $oForecast['weather'][0]['main'],
        $oForecast['weather'][0]['description'],
        $oForecast['weather'][0]['icon'],
        $oForecast['main']['temp'],
        $oForecast['main']['pressure'],
        $oForecast['main']['humidity'],
        $oForecast['wind']['speed']
    );
    var_dump($oJson);
}



