
'use strict';
myApp.factory('Users', ['$resource', function($resource){
  return {
    api: $resource('http://www.traversepoint.com/ci-regen/api/users/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
