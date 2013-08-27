
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/users', {templateUrl: 'app/modules/users/views/users.html', controller: UserListController});
    $routeProvider.when('/user/create', {templateUrl: 'app/modules/users/views/new-user.html' ,controller: UserListController});
    $routeProvider.when('/user/:id', {templateUrl: 'app/modules/users/views/user.html' ,controller: UserViewController});
    $routeProvider.when('/login', {templateUrl: 'app/partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'users'});
  }]);
