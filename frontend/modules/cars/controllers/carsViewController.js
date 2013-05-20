
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


