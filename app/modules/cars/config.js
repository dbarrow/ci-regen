
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/cars', {templateUrl: 'app/modules/cars/views/cars.html', controller: CarListController});
    $routeProvider.when('/car/create', {templateUrl: 'app/modules/cars/views/new-car.html' ,controller: CarListController});
    $routeProvider.when('/car/:id', {templateUrl: 'app/modules/cars/views/car.html' ,controller: CarViewController});
    $routeProvider.when('/login', {templateUrl: 'app/partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'cars'});
  }]);
