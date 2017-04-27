App.config(function($stateProvider, $urlRouterProvider) {
	
	 $urlRouterProvider.otherwise('export');
	 
	 
    $stateProvider
      .state('index', {
		 url:'/',
		  sticky:true,
		 dsr: true,
		templateUrl: './views/index.html',
		controller: 'IndexController as IC'
			
	  })
		
	
	  .state('index.school', {
		 url:'school',
		 templateUrl: './views/school.html',
		controller: 'SchoolController as SC',
		 sticky:true,
		 dsr: true,
	/* 	 views: 
		 {
			 school:{
				
				 
				 
			 }
			 
		 } */
    
      })
	    
	  .state('index.schoolview', {
		 url:'school/:schoolid',
		 sticky:true,
		 dsr: true,
		
		templateUrl: './views/school_view.html',
		controller: 'SchoolViewController as SVC'
				 
		
    
      })
	  .state('index.settings', {
		 url:'settings',
		 sticky:true,
	
		templateUrl: './views/school_view.html',
		controller: 'SchoolViewController as SVC'
				 
				 
		
      }) 
	  
	  .state('index.export', {
		 url:'export',
		 sticky:true,
	
		templateUrl: './views/export.html',
		controller: 'ExportController as EC'
				 
				 
		
      })  
	  
	  .state('index.import', {
		 url:'import',
		 sticky:true,
	
		templateUrl: './views/import.html',
		controller: 'ImportController as IMPC'
				 
				 
		
      })
	  
	  
	 
	  
	  
});