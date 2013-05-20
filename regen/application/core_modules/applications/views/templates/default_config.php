<?php 
$contents = "
myApp.config(['\$routeProvider', '\$httpProvider', function(\$routeProvider, \$httpProvider) {

    \$routeProvider.when('/". $lc_plural ."', {templateUrl: 'app/modules/". $lc_plural ."/views/". $lc_plural .".html', controller: ". $uc_singular ."ListController});
    \$routeProvider.when('/". $lc_singular ."/create', {templateUrl: 'app/modules/". $lc_plural ."/views/new-". $lc_singular .".html' ,controller: ". $uc_singular ."ListController});
    \$routeProvider.when('/". $lc_singular ."/:id', {templateUrl: 'app/modules/". $lc_plural ."/views/". $lc_singular .".html' ,controller: ". $uc_singular ."ViewController});
    \$routeProvider.when('/login', {templateUrl: 'app/partials/login.html', controller: LoginController});

    \$routeProvider.otherwise({redirectTo: '". $lc_plural ."'});
  }]);
";

echo $contents;
?>