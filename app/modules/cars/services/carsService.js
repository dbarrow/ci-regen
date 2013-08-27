
'use strict';
myApp.factory('Cars', ['$resource', function($resource){
  return {
    api: $resource('http://localhost/ci-regen/regen/api/cars/:id',{},{
      update: {
        method: 'PUT'
      }                   
    })
  }
}])
