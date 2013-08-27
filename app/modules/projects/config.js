
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/projects', {templateUrl: 'app/modules/projects/views/projects.html', controller: ProjectListController});
    $routeProvider.when('/project/create', {templateUrl: 'app/modules/projects/views/new-project.html' ,controller: ProjectListController});
    $routeProvider.when('/project/:id', {templateUrl: 'app/modules/projects/views/project.html' ,controller: ProjectViewController});
    $routeProvider.when('/login', {templateUrl: 'app/partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'projects'});
  }]);
