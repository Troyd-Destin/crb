<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRetrieval;


class DataRetrievalController extends Controller
{
    //
	
		function getSchool(Request $request, $schoolCode)
	{
			$school =(new DataRetrieval())->fetchSchools($schoolCode);
			return response()->json($school,200);
		
	}		
	
	function getSchoolList(Request $request)
	{
			$input = $request->all();
			$school =(new DataRetrieval())->fetchSchools(null,$input);
			return response()->json($school,200);
		
	}
	
	
	function exportDataSchoolPopulation(Request $request,$year)
	{
			$response =(new DataRetrieval())->exportDataSchoolPopulation($year);
			$response = ['url' => $request->root().$response];
			return response()->json($response,200);
		
	}	
	
	function exportDataSchoolPopulationDelta(Request $request,$startYear,$endYear)
	{
			$response =(new DataRetrieval())->exportDataSchoolPopulationDelta($startYear,$endYear);
			$response = ['url' => $request->root().$response];
			return response()->json($response,200);
		
	}
	
	function fetchSchoolEnrolmentDataAlbertaEducation(Request $request)
	{
		
			$response =(new DataRetrieval())->fetchSchoolEnrolmentDataAlbertaEducation();
			return response()->json($response,200);
		
	}
	
	function fetchSchoolIndexAlbertaEducation(Request $request)
	{
		
			$response =(new DataRetrieval())->fetchSchoolIndexAlbertaEducation();
			return response()->json($response,200);
		
	}
	
	
}
