<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:500">
    <link href="https://fonts.googleapis.com/css?family=Montserrat+Alternates:500" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/angular.min.js"></script>
    <script src="assets/js/angular-route.min.js"></script>
    <script src="js/app.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-sm bg-dark navbar-inverse">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#MojNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="MojNavbar" ng-controller="glavniController">
                    <h4 class="logo" style="float:left"><img src="assets/img/logo3.png" width="95" alt=""> Bodo Weather Forecast</h4>
                    <nav id="nav" ng-switch="role">
                        <ul class="nav navbar-nav navbar-right" ng-switch-default="guest">
                            <li><a href="#!">PRIJAVA</a></li>
                            <li><a href="#!register">REGISTRACIJA</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right" ng-switch-when="user">
                            <li><a href="#!home">VREMENSKA PROGNOZA</a></li>
                            <li><a href="#!my_cities">MOJI GRADOVI</a></li>
                            <li><a href="#!logout" ng-click="logout()">ODJAVA</a></li>
                        </ul>
                    </nav>
                </div>
        </nav>
    </header>
    <div style="padding-bottom:40px" ng-view></div>
<footer class="navbar navbar-default navbar-fixed-bottom text-center" style="padding-bottom:0px; min-height:0px;">
    <h4 class="logo"><img src="assets/img/logo3.png" width="95" alt=""> Bodo Weather Forecast</h4>
</footer>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>