'use strict';

var controllers = angular.module('controllers', []);

controllers.controller('MainController', ['$scope', '$location', '$window',
    function ($scope, $location, $window) {
        $scope.loggedIn = function() {
            return Boolean($window.sessionStorage.access_token);
        };

        $scope.logout = function () {
            delete $window.sessionStorage.access_token;
            $location.path('/login').replace();
        };
    }
]);

controllers.controller('QuizController', ['$scope', '$http', 
    function ($scope, $http) {
		$scope.quizModel = {};
		$scope.clear = function() {
			$http.post('api/clear')
				.success(function (data) {
					$scope.update();
				});
		}
		
		$scope.update = function() {
			$http.post('api/quiz', $scope.quizModel).success(function (data) {
			   $scope.quizModel = {};
			   if(data.question) {
					$scope.quizModel.question_id = data.question.id;
					$scope.quizModel.dir = data.question.dir;
			   }			   
			   $scope.data = data;
			})
		}
				
        $scope.update();
    }
]);

controllers.controller('LoginController', ['$scope', '$http', '$window', '$location',
    function($scope, $http, $window, $location) {
        $scope.login = function () {
            $scope.submitted = true;
            $scope.error = {};
            $http.post('api/login', $scope.userModel).success(
                function (data) {
                    $window.sessionStorage.access_token = data.access_token;
                    $location.path('/').replace();
            }).error(function (data) {
					if(data.message) {
						$scope.error['critical'] = data.message;
					}else{
						angular.forEach(data, function (error) {
							$scope.error[error.field] = error.message;
						});
					}
                }
            );
        };
    }
]);