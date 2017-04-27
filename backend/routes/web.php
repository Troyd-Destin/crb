<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

 Route::get('/', function () {
    return view('welcome');
});
 
 
 
 Route::get('/test/','DataProcessController@getGISData');

 //Route::get('/cron/schools','DataProcessController@translateAllSchools');
 
 Route::get('/school/process','DataProcessController@translateAllSchools');
 
  // School Misc
 
  Route::get('/school/data-year-ranges','SchoolController@getSchoolDataYearRanges');
 
 
 
 /// School Routes
 Route::post('/school/{schoolCode}','DataProcessController@updateSchool' ); 
 Route::get('/school/{schoolCode}','SchoolController@getSchool');
 Route::get('/school/{schoolCode}/geocode','DataProcessController@translateSchool');
 Route::post('/school/{schoolCode}/delete','SchoolController@deleteSchool');
 Route::get('/school','DataRetrievalController@getSchoolList');
 
 

 //Date Export
 Route::get('/exportdata/year/{year}/population','DataRetrievalController@exportDataSchoolPopulation');
 Route::get('/exportdata/enrolment/delta/startyear/{startYear}/endyear/{endYear}','DataRetrievalController@exportDataSchoolPopulationDelta');
 
 //Data Import
 
 Route::get('/import/education-alberta/school-enrolment','DataRetrievalController@fetchSchoolEnrolmentDataAlbertaEducation');
 Route::get('/import/education-alberta/school-index','DataRetrievalController@fetchSchoolIndexAlbertaEducation');
 
 
 // Data Process
 Route::get('/process/education-alberta/school-enrolment','DataProcessController@processEducationAlbertaDownloads');
 Route::get('/process/education-alberta/school-index','DataProcessController@processEducationAlbertaSchoolIndexDownload');