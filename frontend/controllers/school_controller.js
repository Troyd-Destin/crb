
App.controller('SchoolController',function($scope,$state,$http,NgMap) {
	var vm = this;
	vm.map = null;
	vm.mapZoom = 14;
	vm.table = {};
	vm.table.page = 1;
	vm.table.limit = 5;
	vm.pageChangedCount = 1;

	
	vm.SchoolListSelected = [];
	
	
	console.log($state.current);

	
	vm.init = function ()
	{
		vm.fetchSchoolList();
		console.log('things');
		
	}
	


	NgMap.getMap().then(function(map) {
		vm.map = map;
	  });



	console.log(vm.map);
	
	
	vm.mapClick = function ($event,index,school)
	{
		
		vm.selectedSchool = school;
		vm.selectedSchoolIndex = index;
		if(school)
		{
			vm.map.showInfoWindow('myInfoWindow',this);
		}	
	}
	
	vm.updateSchool = function ($event)
	{
		
		var school = this.info;
		school.longitude = $event.latLng.lng();
		school.latitude = $event.latLng.lat();
		//console.log(school);
		$http.post($scope.IC.api+'/school/'+school.school_code,school).then(function(r){
			//console.log(r.data);
			vm.fetchSchoolList();
			toastr.success('School Updated');
		
			
		})
		//$scope.latlng = [event.latLng.lat(), event.latLng.lng()];
		
	}
	
	vm.goToSchoolView = function (school)
	{
		//console.log(school);
		$scope.IC.current_school = school.school_code;
		$state.go('index.schoolview',{'schoolid':school.school_code});
		
	}
	

	vm.fetchSchoolList = function()
	{
	
	console.log(vm.table);
		 vm.request = $http.get($scope.IC.api+'/school',{'params':vm.table})
				.then(function(response) {
					
					
					vm.response = response.data;
console.log(response.data);
				}).$promise;
								
	}
	vm.init();
		
});


App.controller('SchoolViewController',function($scope,$state,$stateParams,$http,NgMap) {
	
	var vm = this;
	vm.schoolCode = $stateParams.schoolid;
	$scope.IC.current_school = vm.schoolCode;
	vm.schoolObj = {};
	vm.map = null;
	vm.mapZoom = 18;
	vm.manualVerfication = false;

	vm.init = function ()
	{

		vm.getSchool();

	}
	
	vm.getSchool = function ()
	{
		
				
		$http.get($scope.IC.api+'/school/'+vm.schoolCode,null).then(function(r){
			
			console.log(r.data);
			vm.schoolObj = r.data;
			
		});
		
		
		
	}
	
	
	vm.updateSchool = function ($event)
	{
		
		var school = this.info;
		school.longitude = $event.latLng.lng();
		school.latitude = $event.latLng.lat();
		console.log(school);
		$http.post($scope.IC.api+'/school/'+vm.schoolCode,school).then(function(r){
			
			
			vm.schoolObj.longitude = school.longitude;
			vm.schoolObj.latitude = school.latitude;
			
			toastr.success('School Updated');
				
			
		})
		//$scope.latlng = [event.latLng.lat(), event.latLng.lng()];
		
	}
	
	vm.geocodeSchool = function ()
	{
		
		$http.get($scope.IC.api+'/school/'+vm.schoolCode+'/geocode',null).then(function(r){
			
			vm.manualVerfication = true;
			
			vm.schoolObj = r.data;
			
			toastr.success('Geocoded');
		
			
		});
		
	}
	
	vm.deleteSchool = function ()
	{
		$http.post($scope.IC.api+'/school/'+vm.schoolCode+'/delete',null).then(function(r){
			
			toastr.success('Deleted');
			$scope.IC.current_school = null;
			$state.go('index.school');
			//vm.schoolObj = r.data;
			
		});
				
	}

	
	NgMap.getMap().then(function(map) {
		vm.map = map;
	  });

	vm.init();


});
