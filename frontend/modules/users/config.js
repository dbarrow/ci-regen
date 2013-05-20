
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/users', {templateUrl: 'modules/users/views/users.html', controller: UserListController});
    $routeProvider.when('/user/create', {templateUrl: 'modules/users/views/new-user.html' ,controller: UserListController});
    $routeProvider.when('/user/:id', {templateUrl: 'modules/users/views/user.html' ,controller: UserViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'users'});
  }]);
