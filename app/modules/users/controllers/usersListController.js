
function UserListController($rootScope, $location, $scope, $filter, Users, authService)
{	
  $scope = new BaseListController($scope, $filter, $location);

  $scope.$on('event:auth-loginRequired', function() {
    $location.path('/login');
    console.log('login required');
  });

  //table settings
  $scope.sortingOrder = 'id';
  $scope.reverse = true;
  $scope.itemsPerPage = 10; 

  // get all Users and run baselistcontroller function search()
  $scope.items = Users.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(user){
    $location.path('/user/' + user.id);
  }

  $scope.getAll = function(){
    return $scope.items = Users.api.query();
  };

  $scope.create = function(set){
    Users.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/users');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var user = $scope.items[0];
    var desc = user.description;		
    user.description = $scope.searchText;
    Users.api.update({description:desc},{description:user.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(user, idx){     
    Users.api.delete({id:user.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==user.id){
            $scope.items.splice(i,1);
            break;
          }		
        };
        $scope.search(); 
      },
      function(error){
        alert('error');
      }
      );		
  };	  
}


UserListController.$inject = ['$rootScope','$location','$scope','$filter','Users', 'authService']; 