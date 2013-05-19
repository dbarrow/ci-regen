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
;function LoginController($rootScope, $location, $scope, authService, Login)
{
  $scope.login = function(set){
    Login.api.login(set, function(success){
      $location.path("/projects");
     // authService.loginConfirmed(success);

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
        console.log("login conitmed")
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
     api: $resource('http://www.traversepoint.com/ci-regen/api_auth/login',{},{
      login: {
        method: 'POST'
      }  
    }),
     logout: $resource('http://www.traversepoint.com/ci-regen/api_auth/logout',{},{})

   }		
 }]);
  

;
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/api_auth', {templateUrl: 'modules/api_auth/views/api_auth.html', controller: Api_authListController});
    $routeProvider.when('/api_auth/create', {templateUrl: 'modules/api_auth/views/new-api_auth.html' ,controller: Api_authListController});
    $routeProvider.when('/api_auth/:id', {templateUrl: 'modules/api_auth/views/api_auth.html' ,controller: Api_authViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'api_auth'});
  }]);
;
function Api_authListController($rootScope, $location, $scope, $filter, Api_auth, authService)
{	
  $scope = new BaseListController($scope, $filter, $location);

  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required');
  });

  //table settings
  $scope.sortingOrder = 'id';
  $scope.reverse = true;
  $scope.itemsPerPage = 10; 

  // get all Api_auth and run baselistcontroller function search()
  $scope.items = Api_auth.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(api_auth){
    $location.path('/api_auth/' + api_auth.id);
  }

  $scope.getAll = function(){
    return $scope.items = Api_auth.api.query();
  };

  $scope.create = function(set){
    Api_auth.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/api_auth');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var api_auth = $scope.items[0];
    var desc = api_auth.description;		
    api_auth.description = $scope.searchText;
    Api_auth.api.update({description:desc},{description:api_auth.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(api_auth, idx){     
    Api_auth.api.delete({id:api_auth.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==api_auth.id){
            $scope.items.splice(i,1);
            break;
          }		
        };
        $scope.search(); 
      },
      function(error){
        alert('error');
      }
      );		
  };	  
}


Api_authListController.$inject = ['$rootScope','$location','$scope','$filter','Api_auth', 'authService']; ;
function Api_authViewController($location, $scope, $routeParams, Api_auth)
{
  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required')
  });

  $scope.api_auth =Api_auth.api.get({id:$routeParams.id}, function(project) {
  });

  $scope.saveApi_auth = function(api_auth) {
    Api_auth.api.update({description:desc},{description:api_auth.description}, function(success){
      console.log(success)
    },
    function(error){console.log(error)});    
  }
}

Api_authViewController.$inject = ['$location','$scope','$routeParams','Api_auth'];


;
'use strict';
myApp.factory('Api_auth', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/api_auth/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
;
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/cars', {templateUrl: 'modules/cars/views/cars.html', controller: CarListController});
    $routeProvider.when('/car/create', {templateUrl: 'modules/cars/views/new-car.html' ,controller: CarListController});
    $routeProvider.when('/car/:id', {templateUrl: 'modules/cars/views/car.html' ,controller: CarViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'cars'});
  }]);
;
function CarListController($rootScope, $location, $scope, $filter, Cars, authService)
{	
  $scope = new BaseListController($scope, $filter, $location);

  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required');
  });

  //table settings
  $scope.sortingOrder = 'id';
  $scope.reverse = true;
  $scope.itemsPerPage = 10; 

  // get all Cars and run baselistcontroller function search()
  $scope.items = Cars.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(car){
    $location.path('/car/' + car.id);
  }

  $scope.getAll = function(){
    return $scope.items = Cars.api.query();
  };

  $scope.create = function(set){
    Cars.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/cars');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var car = $scope.items[0];
    var desc = car.description;		
    car.description = $scope.searchText;
    Cars.api.update({description:desc},{description:car.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(car, idx){     
    Cars.api.delete({id:car.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==car.id){
            $scope.items.splice(i,1);
            break;
          }		
        };
        $scope.search(); 
      },
      function(error){
        alert('error');
      }
      );		
  };	  
}


CarListController.$inject = ['$rootScope','$location','$scope','$filter','Cars', 'authService']; ;
function CarViewController($location, $scope, $routeParams, Cars)
{
  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required')
  });

  $scope.car =Cars.api.get({id:$routeParams.id}, function(project) {
  });

  $scope.saveCar = function(car) {
    Cars.api.update({description:desc},{description:car.description}, function(success){
      console.log(success)
    },
    function(error){console.log(error)});    
  }
}

CarViewController.$inject = ['$location','$scope','$routeParams','Cars'];


;
'use strict';
myApp.factory('Cars', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/cars/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
;
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/projects', {templateUrl: 'modules/projects/views/projects.html', controller: ProjectListController});
    $routeProvider.when('/project/create', {templateUrl: 'modules/projects/views/new-project.html' ,controller: ProjectListController});
    $routeProvider.when('/project/:id', {templateUrl: 'modules/projects/views/project.html' ,controller: ProjectViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'projects'});
  }]);
;
function ProjectListController($rootScope, $location, $scope, $filter, Projects, authService)
{	
  $scope = new BaseListController($scope, $filter, $location);

  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required');
  });

  //table settings
  $scope.sortingOrder = 'id';
  $scope.reverse = true;
  $scope.itemsPerPage = 10; 

  // get all Projects and run baselistcontroller function search()
  $scope.items = Projects.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(project){
    $location.path('/project/' + project.id);
  }

  $scope.getAll = function(){
    return $scope.items = Projects.api.query();
  };

  $scope.create = function(set){
    Projects.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/projects');
      },
      function(error){
        alert('error');
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
        alert('error');
      }
      );		
  };	  
}


ProjectListController.$inject = ['$rootScope','$location','$scope','$filter','Projects', 'authService']; ;
function ProjectViewController($location, $scope, $routeParams, Projects)
{
  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required')
  });

  $scope.project =Projects.api.get({id:$routeParams.id}, function(project) {
  });

  $scope.saveProject = function(project) {
    Projects.api.update({description:desc},{description:project.description}, function(success){
      console.log(success)
    },
    function(error){console.log(error)});    
  }
}

ProjectViewController.$inject = ['$location','$scope','$routeParams','Projects'];


;
'use strict';
myApp.factory('Projects', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/projects/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
;
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/user_profiles', {templateUrl: 'modules/user_profiles/views/user_profiles.html', controller: User_profileListController});
    $routeProvider.when('/user_profile/create', {templateUrl: 'modules/user_profiles/views/new-user_profile.html' ,controller: User_profileListController});
    $routeProvider.when('/user_profile/:id', {templateUrl: 'modules/user_profiles/views/user_profile.html' ,controller: User_profileViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'user_profiles'});
  }]);
;
function User_profileListController($rootScope, $location, $scope, $filter, User_profiles, authService)
{	
  $scope = new BaseListController($scope, $filter, $location);

  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required');
  });

  //table settings
  $scope.sortingOrder = 'id';
  $scope.reverse = true;
  $scope.itemsPerPage = 10; 

  // get all User_profiles and run baselistcontroller function search()
  $scope.items = User_profiles.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(user_profile){
    $location.path('/user_profile/' + user_profile.id);
  }

  $scope.getAll = function(){
    return $scope.items = User_profiles.api.query();
  };

  $scope.create = function(set){
    User_profiles.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/user_profiles');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var user_profile = $scope.items[0];
    var desc = user_profile.description;		
    user_profile.description = $scope.searchText;
    User_profiles.api.update({description:desc},{description:user_profile.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(user_profile, idx){     
    User_profiles.api.delete({id:user_profile.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==user_profile.id){
            $scope.items.splice(i,1);
            break;
          }		
        };
        $scope.search(); 
      },
      function(error){
        alert('error');
      }
      );		
  };	  
}


User_profileListController.$inject = ['$rootScope','$location','$scope','$filter','User_profiles', 'authService']; ;
function User_profileViewController($location, $scope, $routeParams, User_profiles)
{
  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required')
  });

  $scope.user_profile =User_profiles.api.get({id:$routeParams.id}, function(project) {
  });

  $scope.saveUser_profile = function(user_profile) {
    User_profiles.api.update({description:desc},{description:user_profile.description}, function(success){
      console.log(success)
    },
    function(error){console.log(error)});    
  }
}

User_profileViewController.$inject = ['$location','$scope','$routeParams','User_profiles'];


;
'use strict';
myApp.factory('User_profiles', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/user_profiles/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
;
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/users', {templateUrl: 'modules/users/views/users.html', controller: UserListController});
    $routeProvider.when('/user/create', {templateUrl: 'modules/users/views/new-user.html' ,controller: UserListController});
    $routeProvider.when('/user/:id', {templateUrl: 'modules/users/views/user.html' ,controller: UserViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'users'});
  }]);
;
function UserListController($rootScope, $location, $scope, $filter, Users, authService)
{	
  $scope = new BaseListController($scope, $filter, $location);

  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required');
  });

  //table settings
  $scope.sortingOrder = 'id';
  $scope.reverse = true;
  $scope.itemsPerPage = 10; 

  // get all Users and run baselistcontroller function search()
  $scope.items = Users.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(user){
    $location.path('/user/' + user.id);
  }

  $scope.getAll = function(){
    return $scope.items = Users.api.query();
  };

  $scope.create = function(set){
    Users.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/users');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var user = $scope.items[0];
    var desc = user.description;		
    user.description = $scope.searchText;
    Users.api.update({description:desc},{description:user.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(user, idx){     
    Users.api.delete({id:user.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==user.id){
            $scope.items.splice(i,1);
            break;
          }		
        };
        $scope.search(); 
      },
      function(error){
        alert('error');
      }
      );		
  };	  
}


UserListController.$inject = ['$rootScope','$location','$scope','$filter','Users', 'authService']; ;
function UserViewController($location, $scope, $routeParams, Users)
{
  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required')
  });

  $scope.user =Users.api.get({id:$routeParams.id}, function(project) {
  });

  $scope.saveUser = function(user) {
    Users.api.update({description:desc},{description:user.description}, function(success){
      console.log(success)
    },
    function(error){console.log(error)});    
  }
}

UserViewController.$inject = ['$location','$scope','$routeParams','Users'];


;
'use strict';
myApp.factory('Users', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/users/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
