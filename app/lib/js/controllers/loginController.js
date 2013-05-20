function LoginController($rootScope, $location, $scope, authService, Login)
{
  $scope.login = function(set){
    Login.api.login(set, function(success){
      $location.path("/projects");
     // authService.loginConfirmed(success);

    },
    function(error){
      alert("error");
    }
    );
  };

$scope.logout = function(){
    Login.logout.get(function(success){
      

      $location.path("/login");

      
    },
    function(error){
      alert("error");
    }
    );
  };

}

LoginController.$inject = ['$rootScope','$location','$scope','authService','Login'];
