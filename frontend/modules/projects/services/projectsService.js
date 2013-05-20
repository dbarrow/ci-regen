
'use strict';
myApp.factory('Projects', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/regen/api/projects/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
