<?php
/**
 * Created by PhpStorm.
 * User: DomagojBodo
 * Date: 16.5.2018.
 * Time: 18:01
 */

include "connection.php";
ini_set('memory_limit', '2048M');
header('Content-type: text/json');
header('Content-type: application/json; charset=utf-8');



$sJsonID="";
$user_id="";
$city_id="";

if(isset($_GET['json_id']))
{
    $sJsonID=$_GET['json_id'];
}
if(isset($_GET['user_id']))
{
    $user_id=$_GET['user_id'];
}
if(isset($_GET['city_id']))
{
    $city_id=$_GET['city_id'];
}

$oJson=array();
switch($sJsonID)
{
    case 'get_all_cities':
        $sQuery="SELECT * FROM usercities where ID=".$user_id;
        $oRecord=$oConnection->query($sQuery);

        while($oRow=$oRecord->fetch(PDO::FETCH_BOTH))
        {
            $oCity=new UserCity(
                $oRow['ID'],
                $oRow['CityID']
            );
            array_push($oJson,$oCity);
        }
        break;
    case 'get_city_by_id':
        $sQuery="SELECT * FROM usercities WHERE CityID=".$city_id;
        $oRecord=$oConnection->query($sQuery);

        while($oRow=$oRecord->fetch(PDO::FETCH_BOTH))
        {
            $oUserCity=new UserCity(
                $oRow['ID'],
                $oRow['CityID']
            );
            array_push($oJson,$oUserCity);
        }
        break;
    case 'get_city_forecast':
        $sCurloptUrl = 'http://api.openweathermap.org/data/2.5/forecast?q='.$city_id.'&units=metric&mode=json&appid=5630f4dfb2c7d47e5b34dc623b30ffef';
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
        }
        break;
    case 'get_followed_cities':
        $sCurloptUrl = 'http://api.openweathermap.org/data/2.5/forecast?id='.$city_id.'&mode=json&appid=5630f4dfb2c7d47e5b34dc623b30ffef';
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
        $oGrad = $oResponse['city'];
            $oJson[] = new City(
                $oGrad['id'],
                $oGrad['name'],
                $oGrad['country'],
                $oGrad['coord']['lat'],
                $oGrad['coord']['lon']
            );
        break;
}
echo json_encode($oJson);


