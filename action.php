<?php
/**
 * Created by PhpStorm.
 * User: DomagojBodo
 * Date: 16.5.2018.
 * Time: 23:27
 */
session_start();
include "connection.php";
$sPostData = file_get_contents("php://input");
$oPostData = json_decode($sPostData);
$sActionID= $oPostData->action_id;

if(isset($_POST['action_id']))
{
    $sActionID=$_POST['action_id'];
}

switch ($sActionID)
{
    case 'login':
        $sEmail = $oPostData->email;
        $sPassword = $oPostData->password;

        $sQuery = "SELECT * FROM users WHERE email='$sEmail' AND password='$sPassword'";
        $oRecord = $oConnection->query($sQuery);
        $oRow=$oRecord->fetch(PDO::FETCH_BOTH);
        //$oRecord->fetch(PDO::FETCH_BOTH);
        if($oRecord->rowCount()>0){
            $_SESSION['userID'] = $oRow['ID'];
            $_SESSION['userName'] = $oRow['first_name']." ".$oRow['last_name'];
            $_SESSION['userEmail'] = $oRow['email'];
            echo json_encode(array(
                "status" => 1,
                "user_id" => $_SESSION['userID']
            ));
        }
        else{
            echo json_encode(array(
                "status" => 0
            ));
        }
        break;

    case 'logout':
        session_destroy();
        break;
    case 'check_logged_in':
        if(isset($_SESSION['userID'])){
            echo json_encode(array(
                "status" => 1,
                "user_id" => $_SESSION['userID']
            ));
        }
        else{
            echo json_encode(array(
                "status" => 0
            ));
        }
        break;
    case 'add_new_user':
        $sFirstName = $oPostData->first_name;
        $sLastName = $oPostData->last_name;
        $sEmail = $oPostData->email;
        $sPassword = $oPostData->password;
        $sQuery = "SELECT * FROM users WHERE email='$sEmail'";
        $oRecord = $oConnection->query($sQuery);
        $oRow=$oRecord->fetch(PDO::FETCH_BOTH);
        //$oRecord->fetch(PDO::FETCH_BOTH);
        if($oRecord->rowCount()>0){
            echo "User already exists";
        }
        else{
            //$sQuery = "INSERT INTO users (first_name, last_name, email, password) VALUES ($sFirstName, $sLastName, $sEmail, $sPassword)";
            $sQuery = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
            $oStatement = $oConnection->prepare($sQuery);
            $oData = array(
                'first_name' => $sFirstName,
                'last_name' => $sLastName,
                'email' => $sEmail,
                'password' => $sPassword
            );
            try
            {
                $oStatement->execute($oData);
                //$oStatement = $oConnection->query($sQuery);
                echo 1;
            }
            catch(PDOException $error)
            {
                echo $error;
                echo 0;
            }
        }
        break;
    case 'add_city':
        $sCityName = $oPostData->CityName;
        $sCurloptUrl = 'http://api.openweathermap.org/data/2.5/forecast?q='.$sCityName.'&units=metric&mode=json&appid=5630f4dfb2c7d47e5b34dc623b30ffef';
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
        $oCity = $oResponse['city'];

        $sQuery = "INSERT INTO usercities (ID, CityID) VALUES (:ID, :CityID)";
        $oStatement = $oConnection->prepare($sQuery);
        $oData = array(
            'ID' => $_SESSION['userID'],
            'CityID' => $oCity['id'],
        );
        try
        {
            $oStatement->execute($oData);
            echo 1;
        }
        catch(PDOException $error)
        {
            echo $error;
            echo 0;
        }
        break;
    case 'delete_city':
        $sUserID = $_SESSION['userID'];
        $sCityID = $oPostData->CityID;
        $sQuery = "DELETE FROM usercities WHERE ID=:ID and CityID=:CityID";
        $oStatement = $oConnection->prepare($sQuery);
        $oData = array(
            'ID'=>$sUserID,
            'CityID'=>$sCityID
        );
        try
        {
            $oStatement->execute($oData);
            echo 1;
        }
        catch(PDOException $error)
        {
            echo $error;
            echo 0;
        }
}