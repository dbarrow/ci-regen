
function ProjectListController($rootScope, $location, $scope, $filter, Projects, authService)
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

  // get all Projects and run baselistcontroller function search()
  $scope.items = Projects.api.query(
    function(success){
      $scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  $scope.view = function(project){
    $location.path('/project/' + project.id);
  }

  $scope.getAll = function(){
    return $scope.items = Projects.api.query();
  };

  $scope.create = function(set){
    Projects.api.save(set, 
      function(success){
        $scope.items.push(success);		
        $location.path('/projects');
      },
      function(error){
        alert('error');
      }
    );
  };

  $scope.put = function(idx)
  {			
    var project = $scope.items[0];
    var desc = project.description;		
    project.description = $scope.searchText;
    Projects.api.update({description:desc},{description:project.description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  $scope.delete = function(project, idx){     
    Projects.api.delete({id:project.id},
      function(success){
        $scope.pagedItems.splice(idx, 1);
        for(var i in $scope.items){
          if($scope.items[i].id==project.id){
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


ProjectListController.$inject = ['$rootScope','$location','$scope','$filter','Projects', 'authService']; 