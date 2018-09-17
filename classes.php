<?php
/**
 * Created by PhpStorm.
 * User: DomagojBodo
 * Date: 15.5.2018.
 * Time: 9:06
 */

class Configuration{
    public $host="localhost";
    public $dbName="vremenskaprognoza";
    public $username="root";
    public $password="";
}

class User
{
    public $ID = "N/A";
    public $first_name = "N/A";
    public $last_name = "N/A";
    public $email = "N/A";
    public $password = "N/A";
    public function __construct($ID=null,$first_name=null,$last_name=null,$email=null,$password=null){
        if($ID) $this->ID=$ID;
        if($first_name) $this->first_name=$first_name;
        if($last_name) $this->last_name=$last_name;
        if($email) $this->email=$email;
        if($password) $this->password=$password;
    }
}

class City
{
    public $CityID = "N/A";
    public $CityName = "N/A";
    public $CountryName = "N/A";
    public $lat = "N/A";
    public $lon = "N/A";
    public $dt_txt = "N/A";
    public $weather = "N/A";
    public $description = "N/A";
    public $icon = "N/A";
    public $temperature = "N/A";
    public $pressure = "N/A";
    public $humidity = "N/A";
    public $speed = "N/A";
	public function __construct($CityID=null,$CityName=null,$CountryName=null, $lat=null, $lon=null, $dt_txt=null, $weather=null, $description=null,$icon=null,$temperature=null,$pressure=null,$humidity=null, $speed=null){
        if($CityID) $this->CityID=$CityID;
        if($CityName) $this->CityName=$CityName;
        if($CountryName) $this->CountryName=$CountryName;
        if($lat) $this->lat=$lat;
        if($lon) $this->lon=$lon;
        if($dt_txt) $this->dt_txt=$dt_txt;
        if($weather) $this->weather=$weather;
        if($description) $this->description=$description;
        if($icon) $this->icon=$icon;
        if($temperature) $this->temperature=$temperature;
        if($pressure) $this->pressure=$pressure;
        if($humidity) $this->humidity=$humidity;
        if($speed) $this->speed=$speed;
    }
}


class UserCity
{
    public $ID = "N/A";
    public $CityID = "N/A";
    public function __construct($ID = null, $CityID = null){
        if($ID) $this->ID=$ID;
        if($CityID) $this->CityID=$CityID;
    }
}


