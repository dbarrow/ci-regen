'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.services','myApp.http-auth-interceptor' ,'myApp.directives', 'myApp.loginService']).
config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/projects', {templateUrl: 'partials/projects.html', controller: ProjectListCtrl});
    $routeProvider.when('/view2', {templateUrl: 'partials/partial2.html'});
    $routeProvider.when('/project/create', {templateUrl: 'partials/new-project.html' ,controller: ProjectListCtrl});
    $routeProvider.when('/project/:id', {templateUrl: 'partials/project-view.html' ,controller: ProjectViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: '/projects'});
  }]);


;'use strict';

function BaseListController($scope, $filter, $location) {    
    $scope.filteredItems = [];
    $scope.groupedItems = [];
    $scope.pagedItems = [];
    $scope.currentPage = 0;  
    $scope.searchMatch = function(haystack, needle) {
      if (!needle) {
         return true;
     }       
     if (typeof(haystack)=="function" || haystack==null) {
         return false;      
     }
     return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
 };

    // init the filtered items
    $scope.search = function () {
        $scope.filteredItems = $filter('filter')($scope.items, function (item) {
            for(var attr in item) {
                if ($scope.searchMatch(item[attr], $scope.query))
                    return true;
            }
            return false;
        });
        // take care of the sorting order
        if ($scope.sortingOrder !== '') {
            $scope.filteredItems = $filter('orderBy')($scope.filteredItems, $scope.sortingOrder, $scope.reverse);
        }
        $scope.currentPage = 0;
        // now group by pages
        $scope.pagedItems = $scope.groupToPages();
    };
    
    // calculate page in place
    $scope.groupToPages = function () {
        $scope.pagedItems = [];
        
        for (var i = 0; i < $scope.filteredItems.length; i++) {
            if (i % $scope.itemsPerPage === 0) {
                $scope.pagedItems[Math.floor(i / $scope.itemsPerPage)] = [$scope.filteredItems[i]];
            } else {
                $scope.pagedItems[Math.floor(i / $scope.itemsPerPage)].push($scope.filteredItems[i]);
            }
        }

        return $scope.pagedItems;
    };
    
    $scope.range = function (start, end) {
        var ret = [];
        if (!end) {
            end = start;
            start = 0;
        }
        for (var i = start; i < end; i++) {
            ret.push(i);
        }
        return ret;
    };
    
    $scope.prevPage = function () {
        if ($scope.currentPage > 0) {
            $scope.currentPage--;
        }
    };
    
    $scope.nextPage = function () {
        if ($scope.currentPage < $scope.pagedItems.length - 1) {
            $scope.currentPage++;
        }
    };
    
    $scope.setPage = function () {
        $scope.currentPage = this.n;
    };

    // functions have been describe process the data for display


    // change sorting order
    $scope.sort_by = function(newSortingOrder) {
        if ($scope.sortingOrder == newSortingOrder)
            $scope.reverse = !$scope.reverse;

        $scope.sortingOrder = newSortingOrder;

        // icon setup
        /*
        $('th i').each(function(){
            
            $(this).removeClass().addClass('icon-sort');
        });
        if ($scope.reverse)
            $('th.'+new_sorting_order+' i').removeClass().addClass('icon-chevron-up');
        else
            $('th.'+new_sorting_order+' i').removeClass().addClass('icon-chevron-down');*/
    };

    return $scope;
}/* Controllers */

BaseListController.$inject = ['$scope', '$filter', '$location'];
;'use strict';

/* Directives */


angular.module('myApp.directives', []).
  directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
  }]);
;'use strict';

/* Filters */

angular.module('myApp.filters', []).
  filter('interpolate', ['version', function(version) {
    return function(text) {
      return String(text).replace(/\%VERSION\%/mg, version);
    }
  }]);
;'use strict';

function ProjectListCtrl($rootScope, $location, $scope, $filter, Projects, authService)
{	
 $scope = new BaseListController($scope, $filter, $location);

 $scope.$on('event:auth-loginRequired', function() {
      $location.path("/login");
          console.log("login required");
        });
        $scope.$on('event:auth-loginConfirmed', function() {
                    console.log("confirmed");

        });


 $scope.sortingOrder = "id";
 $scope.reverse = true;
 $scope.itemsPerPage = 10;
 

 $scope.items = Projects.api.query(function(success){
     $scope.search();              	    	
 },
 function(error){
        alert("error");
      });	



$scope.view = function(project){
    $location.path("/project/" + project.id);
}
 $scope.getAll = function(){
     return $scope.items = Projects.api.query();
 };

 $scope.getByDesc = function(){
     $scope.items = Projects.api.query({description:$scope.searchText});
 };

 $scope.create = function(set){
     Projects.api.save(set, function(success){
      $scope.items.push(success);		
      $location.path("/projects");
    },
    function(error){
        alert("error");
      }
    );
 };

 $scope.put = function(idx)
 {			
     var project = $scope.items[0];
     var desc = project.description;		
     project.description = $scope.searchText;
     Projects.api.update({description:desc},{description:project.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
 };



 $scope.delete = function(project, idx){     
   Projects.api.delete({id:project.id},
    function(success){
     $scope.pagedItems.splice(idx, 1);
     for(var i in $scope.items){
      if($scope.items[i].id==project.id){
        $scope.items.splice(i,1);
        break;
      }		

    };
    $scope.search(); 
  },
  function(error){
   alert("error");
 }
 );		
 };	  

}

ProjectListCtrl.$inject = ['$rootScope','$location','$scope','$filter','Projects','authService'];

function ProjectViewController($location, $scope, $routeParams, Projects)
{

  $scope.$on('event:auth-loginRequired', function() {
      $location.path("/login");
          console.log("login required")
        });
        $scope.$on('event:auth-loginConfirmed', function() {
                    console.log("confirmed")
                    authService.retryAll();
        });


  $scope.project = Projects.api.get({id:$routeParams.id}, function(project) {
  });

  $scope.saveProject = function(project) {
     Projects.api.update({description:desc},{description:project.description}, function(success){
      console.log(success)
    },
      function(error){console.log(error)});    
  }
}

ProjectViewController.$inject = ['$location', '$scope', '$routeParams', 'Projects'];

function LoginController($rootScope, $location, $scope, authService, Login)
{
  $scope.login = function(set){
    Login.api.login(set, function(success){
      $location.path("/projects");
      authService.loginConfirmed(success);

    },
    function(error){
      alert("error");
    }
    );
  };

$scope.logout = function(){
    Login.logout.get(function(success){
      

      $location.path("/login");

      
    },
    function(error){
      alert("error");
    }
    );
  };

}

LoginController.$inject = ['$rootScope','$location','$scope','authService','Login'];

;'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service


angular.module('myApp.services', ['ngResource'])
	//The factory returns objects / functions that can be used by the controllers
    .config(['$httpProvider', function($httpProvider){    	
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
        delete $httpProvider.defaults.headers.common["X-Requested-With"];
       // $httpProvider.defaults.headers.common["api"] = "2345IJASFDSA4KJH!JK";
    }])

    .factory('Projects', ['$resource', '$http', '$rootScope', function($resource, $http, $rootScope){

        return {
        	//api: $resource('http://www.traversepoint.com/ci-regen/api/projects/:id',{},{
            api: $resource('http://api.traversepoint.com/api/projects/:id',{},{
                update: {
                  method: 'PUT'
                }    			    			
    		})
        }
        	
	        
		
	}])

    ;/*global angular:true, browser:true */

/**
 * @license HTTP Auth Interceptor Module for AngularJS
 * (c) 2012 Witold Szczerba
 * License: MIT
 */
(function () {
  'use strict';
  
  angular.module('myApp.http-auth-interceptor', ['http-auth-interceptor-buffer'])

  .factory('authService', ['$rootScope','httpBuffer', function($rootScope, httpBuffer) {
    return {
      /**
       * call this function to indicate that authentication was successfull and trigger a 
       * retry of all deferred requests.
       * @param data an optional argument to pass on to $broadcast which may be useful for
       * example if you need to pass through details of the user that was logged in
       */
      loginConfirmed: function(data) {
        $rootScope.$broadcast('event:auth-loginConfirmed', data);
        httpBuffer.retryAll();
      }
    };
  }])

  /**
   * $http interceptor.
   * On 401 response (without 'ignoreAuthModule' option) stores the request 
   * and broadcasts 'event:angular-auth-loginRequired'.
   */
  .config(['$httpProvider', function($httpProvider) {
    
    var interceptor = ['$rootScope', '$q', 'httpBuffer', function($rootScope, $q, httpBuffer) {
      function success(response) {
        return response;
      }
 
      function error(response) {

        

        if (response.status === 401 && !response.config.ignoreAuthModule) {
          var deferred = $q.defer();
          httpBuffer.append(response.config, deferred);
          $rootScope.$broadcast('event:auth-loginRequired');
          return deferred.promise;
        }
        // otherwise, default behaviour
        return $q.reject(response);
      }
 
      return function(promise) {
        return promise.then(success, error);
      };
 
    }];
    $httpProvider.responseInterceptors.push(interceptor);
  }]);
  
  /**
   * Private module, an utility, required internally by 'http-auth-interceptor'.
   */
  angular.module('http-auth-interceptor-buffer', [])

  .factory('httpBuffer', ['$injector', function($injector) {
    /** Holds all the requests, so they can be re-requested in future. */
    var buffer = [];
    
    /** Service initialized later because of circular dependency problem. */
    var $http; 
    
    function retryHttpRequest(config, deferred) {
      function successCallback(response) {
        deferred.resolve(response);
      }
      function errorCallback(response) {
        deferred.reject(response);
      }
      $http = $http || $injector.get('$http');
      $http(config).then(successCallback, errorCallback);
    }
    
    return {
      /**
       * Appends HTTP request configuration object with deferred response attached to buffer.
       */
      append: function(config, deferred) {
        buffer.push({
          config: config, 
          deferred: deferred
        });      
      },
              
      /**
       * Retries all the buffered requests clears the buffer.
       */
      retryAll: function() {
        for (var i = 0; i < buffer.length; ++i) {
          retryHttpRequest(buffer[i].config, buffer[i].deferred);
        }
        buffer = [];
      }
    };
  }]);
})();;'use strict';

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
     api: $resource('http://api.traversepoint.com/api_auth/login',{},{
      login: {
        method: 'POST'
      }  
    }),
     logout: $resource('http://api.traversepoint.com/api_auth/logout',{},{})

   }		
 }]);
  

