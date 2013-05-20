
function CarListController($rootScope, $location, $scope, $filter, Cars, authService)
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

  // get all Cars and run baselistcontroller function search()
  $scope.items = Cars.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(car){
    $location.path('/car/' + car.id);
  }

  $scope.getAll = function(){
    return $scope.items = Cars.api.query();
  };

  $scope.create = function(set){
    Cars.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/cars');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var car = $scope.items[0];
    var desc = car.description;		
    car.description = $scope.searchText;
    Cars.api.update({description:desc},{description:car.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(car, idx){     
    Cars.api.delete({id:car.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==car.id){
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


CarListController.$inject = ['$rootScope','$location','$scope','$filter','Cars', 'authService']; 