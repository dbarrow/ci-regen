<?php 


$contents = "
function ". $uc_singular ."ListController(\$rootScope, \$location, \$scope, \$filter, " . $uc_plural . ", authService)
{	
  \$scope = new BaseListController(\$scope, \$filter, \$location);

  \$scope.\$on('event:auth-loginRequired', function() {
    \$location.path('/login');
    console.log('login required');
  });

  //table settings
  \$scope.sortingOrder = 'id';
  \$scope.reverse = true;
  \$scope.itemsPerPage = 10; 

  // get all ". $uc_plural ." and run baselistcontroller function search()
  \$scope.items = ". $uc_plural .".api.query(
    function(success){
      \$scope.search();              	    	
    },
    function(error){
      alert('error');
  });	

  \$scope.view = function(". $lc_singular ."){
    \$location.path('/". $lc_singular ."/' + ". $lc_singular .".id);
  }

  \$scope.getAll = function(){
    return \$scope.items = ". $uc_plural .".api.query();
  };

  \$scope.create = function(set){
    ". $uc_plural .".api.save(set, 
      function(success){
        \$scope.items.push(success);		
        \$location.path('/". $lc_plural ."');
      },
      function(error){
        alert('error');
      }
    );
  };

  \$scope.put = function(idx)
  {			
    var ". $lc_singular ." = \$scope.items[0];
    var desc = ". $lc_singular .".description;		
    ". $lc_singular .".description = \$scope.searchText;
    ". $uc_plural .".api.update({description:desc},{description:". $lc_singular .".description}, function(success)
      {console.log(success)},
      function(error){console.log(error)});		
  };

  \$scope.delete = function(". $lc_singular .", idx){     
    ". $uc_plural .".api.delete({id:". $lc_singular .".id},
      function(success){
        \$scope.pagedItems.splice(idx, 1);
        for(var i in \$scope.items){
          if(\$scope.items[i].id==". $lc_singular .".id){
            \$scope.items.splice(i,1);
            break;
          }		
        };
        \$scope.search(); 
      },
      function(error){
        alert('error');
      }
      );		
  };	  
}


". $uc_singular ."ListController.\$inject = ['\$rootScope','\$location','\$scope','\$filter','". $uc_plural ."', 'authService']; "

;

echo $contents;
?>