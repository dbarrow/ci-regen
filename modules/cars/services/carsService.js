
'use strict';
myApp.factory('Cars', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/cars/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
