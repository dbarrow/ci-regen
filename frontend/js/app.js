'use strict';

// Declare app level module which depends on filters, and services
var myApp = angular.module('myApp', ['myApp.filters', 'myApp.services','myApp.http-auth-interceptor' ,'myApp.directives', 'myApp.loginService'])

.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {
       $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});
 }]);

angular.module('myApp.services', ['ngResource'])

.config(['$httpProvider', function($httpProvider){      
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
  delete $httpProvider.defaults.headers.common['X-Requested-With'];
}])






