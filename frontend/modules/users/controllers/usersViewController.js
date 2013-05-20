
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


