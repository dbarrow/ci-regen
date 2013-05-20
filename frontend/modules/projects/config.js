
myApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/projects', {templateUrl: 'modules/projects/views/projects.html', controller: ProjectListController});
    $routeProvider.when('/project/create', {templateUrl: 'modules/projects/views/new-project.html' ,controller: ProjectListController});
    $routeProvider.when('/project/:id', {templateUrl: 'modules/projects/views/project.html' ,controller: ProjectViewController});
    $routeProvider.when('/login', {templateUrl: 'partials/login.html', controller: LoginController});

    $routeProvider.otherwise({redirectTo: 'projects'});
  }]);
