<?php 
$contents = "
'use strict';
myApp.factory('".$uc_plural."', ['\$resource', function(\$resource){
  return {
    api: \$resource(". base_url() . "api/".$lc_plural."/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
";

echo $contents;
?>