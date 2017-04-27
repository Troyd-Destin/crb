


App.controller('IndexController',function($scope,$state) {
				var vm = this;
				vm.current_school = null;
				
				vm.googleMapsKey = 'AIzaSyDy0dIlvwlzUJzYBht4x0N3kV0Bw9EIjG0';
				vm.api = 'http://'+window.location.hostname+'/crb/backend/public';
	
				$scope.$state = $state;
				$scope.googleMapsUrl="https://maps.googleapis.com/maps/api/js?key="+vm.googleMapsKey;
				
				
				  
				  console.log($state.current);

});
