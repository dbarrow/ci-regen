
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


