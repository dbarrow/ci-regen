
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/cars', {templateUrl: 'modules/cars/views/cars.html', controller: CarListController});
    $routeProvider.when('/car/create', {templateUrl: 'modules/cars/views/new-car.html' ,controller: CarListController});
    $routeProvider.when('/car/:id', {templateUrl: 'modules/cars/views/car.html' ,controller: CarViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'cars'});
  }]);
