'use strict';

angular.module('myApp.loginService', ['ngResource'])

  .config(['$httpProvider', function($httpProvider){      
   $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
   delete $httpProvider.defaults.headers.common["X-Requested-With"];

   var interceptor = ['$rootScope', '$q', function($rootScope, $q) {
    function success(response) {
      if(response.headers('API') || response.headers('API')=="null"){
        window.localStorage.setItem("apikey", response.headers('API'));
        console.log(response.headers('API'));
      }


      $httpProvider.defaults.headers.common["API"] = window.localStorage.getItem("apikey");
      return response;
    }

    function error(response) {}

    return function(promise) {
      return promise.then(success, error);
    };

  }];
  $httpProvider.responseInterceptors.push(interceptor);
}])

  .factory('Login', ['$resource', '$http', '$rootScope', function($resource, $http, $rootScope){

   return {
     api: $resource('http://www.traversepoint.com/ci-regen/api_auth/login',{},{
      login: {
        method: 'POST'
      }  
    }),
     logout: $resource('http://www.traversepoint.com/ci-regen/api_auth/logout',{},{})

   }		
 }]);
  

