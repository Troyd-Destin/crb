App.controller('ImportController',function($scope,$state,$http) {
	
	
	var vm = this;
	vm.indexUpdate = {};
	vm.enrolmentUpdate = {};
	vm.missingGeocodes;

	
	
	vm.init = function (){
		
	
	}
	
	
	vm.EducationAlbertaIndexImport = function ()
	{
	
		vm.indexUpdate.loading = true;
		vm.indexUpdate.import_loading = true;
		$http.get($scope.IC.api+'/import/education-alberta/school-index').then(function(r){

			vm.indexUpdate.import_loading = false;
		
			vm.EducationAlbertaIndexProcess();
		})
		
	}
	
	
	vm.EducationAlbertaIndexProcess = function ()
	{
		
		vm.indexUpdate.process_loading = true;
		$http.get($scope.IC.api+'/process/education-alberta/school-index').then(function(r){
			
			vm.indexUpdate.process_loading = false;
			vm.indexUpdate.loading = false;
			vm.indexUpdate.import = true;
			vm.missingGeocodes = r.data;
			
		})
		
	}
	
	
	vm.EducationAlbertaEnrolmentImport = function ()
	{
	
		vm.enrolmentUpdate.loading = true;
		vm.enrolmentUpdate.import_loading = true;
		$http.get($scope.IC.api+'/import/education-alberta/school-enrolment').then(function(r){

			vm.enrolmentUpdate.import_loading = false;
		
			vm.EducationAlbertaEnrolmentProcess();
		})
		
	}
	
	
	vm.EducationAlbertaEnrolmentProcess = function ()
	{
		
		vm.enrolmentUpdate.process_loading = true;
		$http.get($scope.IC.api+'/process/education-alberta/school-enrolment').then(function(r){
			
			vm.enrolmentUpdate.process_loading = false;
			vm.enrolmentUpdate.loading = false;
			vm.enrolmentUpdate.import = true;
			
		})
		
	}
	

	vm.goToSchoolView = function (school)
	{
		//console.log(school);
		$scope.IC.current_school = school.school_code;
		$state.go('index.schoolview',{'schoolid':school.school_code});
		
	}
	

	
	vm.init();
	
});