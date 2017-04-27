var App = angular.module('App',[

'ngMap',
'ngMaterial',
'ui.router',
'datatables',  
'ct.ui.router.extras.sticky',
 'ct.ui.router.extras.dsr', 
 'ct.ui.router.extras.previous',
 'md.data.table',
 
 ]);






/// Toastr config

toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}