
'use strict';
myApp.factory('Users', ['$resource', function($resource){
  return {
    api: $resource('http://localhost/ci-regen/regen/api/users/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
