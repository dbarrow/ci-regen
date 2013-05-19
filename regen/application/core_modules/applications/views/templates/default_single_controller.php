<?php 
$contents = "
function ".$uc_singular ."ViewController(\$location, \$scope, \$routeParams, ".$uc_plural.")
{
  \$scope.\$on('event:auth-loginRequired', function() {
    \$location.path('/login');
    console.log('login required')
  });

  \$scope.".$lc_singular." =".$uc_plural.".api.get({id:\$routeParams.id}, function(project) {
  });

  \$scope.save".$uc_singular ." = function(".$lc_singular.") {
    ".$uc_plural.".api.update({description:desc},{description:".$lc_singular.".description}, function(success){
      console.log(success)
    },
    function(error){console.log(error)});    
  }
}

".$uc_singular ."ViewController.\$inject = ['\$location','\$scope','\$routeParams','".$uc_plural."'];


";

echo $contents;
?>