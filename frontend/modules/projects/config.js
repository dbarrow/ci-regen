
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/projects', {templateUrl: 'frontend/modules/projects/views/projects.html', controller: ProjectListController});
    $routeProvider.when('/project/create', {templateUrl: 'frontend/modules/projects/views/new-project.html' ,controller: ProjectListController});
    $routeProvider.when('/project/:id', {templateUrl: 'frontend/modules/projects/views/project.html' ,controller: ProjectViewController});
    $routeProvider.when('/login', {templateUrl: 'frontend/partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'projects'});
  }]);
