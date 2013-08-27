
'use strict';
myApp.factory('Projects', ['$resource', function($resource){
  return {
    api: $resource('http://localhost/ci-regen/regen/api/projects/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
