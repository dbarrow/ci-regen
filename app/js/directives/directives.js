'use strict';

/* Directives */


angular.module('myApp.directives', []).
  directive('menu', function() {
  	var navWrap = '<ul>';
  	var navEnd = '</ul>';
    return {
    	restrict: 'E',
    	template: navWrap + '<li>Menu Dir.</li>' + navEnd
    }
  });
