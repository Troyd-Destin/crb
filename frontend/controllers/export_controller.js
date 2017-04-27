App.controller('ExportController',function($scope,$state,$http) {
	
	
	var vm = this;
	vm.SchoolDataYearRanges;
	vm.selectedSchoolPopulationYear;
	vm.selectedSchoolDeltaPopulationEndingYear;
	vm.selectedSchoolDeltaPopulationStartingYear;
	vm.possibleSchoolPopulationYears = [2014,2015,2016];
	
	
	vm.init = function (){
		
		vm.getSchoolDataYearRanges();
	}
	
	
	
	
	
	vm.exportDataSchoolPopulation = function ()
	{
		
		toastr.info('Processing...');
		$http.get($scope.IC.api+'/exportdata/year/'+vm.selectedSchoolPopulationYear+'/population').then(function(r){
			//console.log(r.data);
			
			toastr.success('Created.');
		
			window.location.assign(r.data.url);
		})
		
	}
	
	vm.exportDataSchoolDeltaPopulation = function ()
	{
		if(vm.selectedSchoolDeltaPopulationStartingYear >= vm.selectedSchoolDeltaPopulationEndingYear)
		{
			toastr.error('Please select an ending year greater then your starting year.');
			return;
		}
		toastr.info('Processing...');
		$http.get($scope.IC.api+'/exportdata/enrolment/delta/startyear/'+vm.selectedSchoolDeltaPopulationStartingYear+'/endyear/'+vm.selectedSchoolDeltaPopulationEndingYear).then(function(r){
			//console.log(r.data);
			
			toastr.success('Created.');
		
			window.location.assign(r.data.url);
		})
		
	}
	
	vm.getSchoolDataYearRanges = function ()
	{
		
		//toastr.info('Processing...');
		$http.get($scope.IC.api+'/school/data-year-ranges').then(function(r){
			
			vm.SchoolDataYearRanges = r.data;
		
		})
		
	}
	
	vm.init();
	
});