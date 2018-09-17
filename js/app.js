var app = angular.module('app', ['ngRoute']);

app.config(function($routeProvider){
   $routeProvider
       .when("/", {
           templateUrl: "views/guest/login.html",
           controller: "glavniController"
       })
       .when("/home", {
           templateUrl: "views/user/forecast.html",
           controller: "glavniController"
       })
       .when("/register", {
           templateUrl: "views/guest/register.html",
           controller: "registerCtrl"
       })
       .when("/logout", {
           templateUrl: "views/user/logout.html",
           controller: "glavniController"
       })
       .when("/my_cities", {
           templateUrl: "views/user/my_cities.html",
           controller: "mojiGradovi"
       })
       .otherwise({
           redirectTo: '/'
       });
});

app.controller('registerCtrl', function($scope, $http, $location){
    $scope.Register = function(){
        var oData = {
            'action_id':'add_new_user',
            'first_name': $scope.first_name,
            'last_name': $scope.last_name,
            'email': $scope.email,
            'password': $scope.password
        };
        $http.post('action.php', oData)
            .then(
                function(response){
                    if(response.data==1){
                        alert('Registracija uspješna!');
                        $location.path('/');
                    }
                    else{
                        alert('Registracija ne uspješna, korisnik već postoji!');
                    }
                    console.log(response);
                }
            );
    }
});

app.controller('glavniController', function($scope, $http, $location){
    $scope.loggedin = false;
    $scope.loggeduser = null;
        $http.post('action.php', {action_id: 'check_logged_in'})
            .then(
                function (response) {
                    if (response.data.status == 1) {
                        $scope.role = 'user';
                        $scope.loggedin = true;
                        $location.path('/home');
                    }
                    else {
                        $scope.role = 'guest';
                        $scope.loggedin = false;
                        $location.path('/');
                    }
                },
                function (e) {
                    console.log('error');
                    $scope.ulogiran = false;
                }
            );
    $scope.Prijava = function(){
        var oData = {
            'action_id': 'login',
            'email': $scope.email,
            'password': $scope.password
        };
        $http.post('action.php', oData)
            .then(
                function(response){
                    if(response.data.status == 1){
                        $scope.loggeduser = response.data.user_id;
                        $scope.role = 'user';
                        $scope.loggedin = true;
                        $location.path('/home');
                        location.reload();
                    }
                    else{
                        alert('Incorrect credentials. Try Again!');
                    }
                    console.log(response);
                },
                function(e){
                    console.log('error');
                }
            );
    };
    $scope.logout = function(){
        $http.post('action.php', {action_id: 'logout'})
            .then(
                function(response){
                    $scope.loggedin = false;
                    $scope.role = 'guest';
                    alert('Logout successful');
                    $location.path('/');
                    location.reload();
                },
                function(e){
                    console.log('error');
                }
            );
    }
    $scope.DohvatiPrognozuGrada = function(city) {
        $http({
            method: "GET",
            url: "json.php?json_id=get_city_forecast&city_id="+city
        }).then(function (response) {
            $scope.oVrijeme = response.data;
            $scope.prikazi = true;
            $scope.dates = response.data;
            console.log($scope.dates);
        }, function (response) {
            alert('Greška, pokušajte ponovno!');
        });
    }
});

app.controller('mojiGradovi', function($scope, $http, modal){
    function dohvatiSpremljeneGradove(id) {
        return $http({
            method: "GET",
            url: 'json.php?json_id=get_all_cities&user_id='+id
        });
    }
    function dohvatiInformacijeGradovi(cityID){
        return $http({
            method: "GET",
            url: "json.php?json_id=get_followed_cities&city_id="+ cityID
        })
    }
    function PrikaziGradove() {
        $http.post('action.php', {action_id: 'check_logged_in'}).then(function (res) {
            $scope.loggeduser = res.data.user_id;
            $scope.oGradovi = [];
            dohvatiSpremljeneGradove($scope.loggeduser).then(function (response) {
                console.log($scope.loggeduser);
                var r = response.data;
                for (var i = 0; i < r.length; i++) {
                    dohvatiInformacijeGradovi(r[i].CityID).then(function (odgovor) {
                        $scope.oGradovi.push(odgovor.data);
                    }, function (odgovor) {
                    });
                }
            }, function (response) {
                alert('Pogreška pri učitavanju tablice');
            });
        });
    }
    PrikaziGradove();
   $scope.DodajGrad = function(){
       var oData={
           'action_id':'add_city',
           'CityName': $scope.CityName
       };
       $http.post('action.php', oData)
           .then(
               function (response) {
                   if (response.data == 1) {
                       alert('Zapratili ste grad '+$scope.CityName+' uspješno');
                       PrikaziGradove();
                   }
                   else {
                       alert('Uneseni grad ne postoji, pokušajte ponovno!');
                   }
                   console.log(response);
               }
           );
   }
   $scope.PrikaziVremenskuPrognozu= function(cityName){
       modal.otvoriModal();
           $http({
               method: "GET",
               url: "json.php?json_id=get_city_forecast&city_id="+cityName[0].CityName
           }).then(function (response) {
               $scope.oPrognoza = response.data;
               console.log(response);
           }, function (response) {
               alert('Uneseni grad ne postoji, pokušajte ponovno');
           });
   }

    $scope.ObrisiGrad = function(cityID, $event){
        $event.stopPropagation();
        var oData={
            'action_id':'delete_city',
            'CityID': cityID[0].CityID
        };
        $http.post('action.php', oData)
            .then(
                function (response) {
                    if (response.data == 1) {
                        alert('Uspješno ste obrisali grad!');
                        PrikaziGradove();
                    }
                    else {
                        alert('Niste uspjeli obrisati grad!');
                    }
                }
            );
    }

});

app.filter("mydate", function() {
    return function convertDate(date){
        return new Date(date);
    };
});

app.service('modal', function(){
    this.otvoriModal = function(sHref)
    {
        $('#modals').removeData('bs.modal');
        $('#modals').modal
        ({
            remote: sHref,
            show: true
        });
    };
});


/*$scope.trenutnaVrijednost = 0;
$scope.poslijednjaVrijednost = 0;
for (var i = 0; i < response.data.length; i++) {
    $scope.trenutnaVrijednost = i;
    $scope.date = response.data[$scope.trenutnaVrijednost].dt_txt.toString();
    $scope.res = $scope.date.substr(8, 2);
    $scope.dateNew =  response.data[$scope.poslijednjaVrijednost].dt_txt.toString();
    $scope.resNew = $scope.dateNew.substr(8, 2);
    if($scope.res != $scope.resNew){
        $scope.novaTablica = response.data[i].dt_txt;
        $scope.oVrijeme = response.data[i];
    }
    else{
        $scope.oVrijeme = response.data[i];
    }
    console.log($scope.oVrijeme);
    $scope.trenutnaVrijednost=$scope.poslijednjaVrijednost;
}*/


