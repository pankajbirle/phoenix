'use strict';

/* Declare app level module which depends on views, and components */
angular.module('socialApp', [
     'ngRoute',
     'socialApp.message',
     'socialApp.user',
     'socialApp.feed'   
])
 .config(['$routeProvider', function($routeProvider) {
    $routeProvider.otherwise({redirectTo: '/user'});
}]);
