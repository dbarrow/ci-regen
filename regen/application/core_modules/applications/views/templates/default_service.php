<?php 
$contents = "
'use strict';
myApp.factory('".$uc_plural."', ['\$resource', function(\$resource){
  return {
    api: \$resource('http://www.traversepoint.com/ci-regen/regen/api/".$lc_plural."/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
";

echo $contents;
?>