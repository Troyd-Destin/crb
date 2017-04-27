<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\School;

class SchoolController extends Controller
{
    //
	
	function getSchoolDataYearRanges(Request $request)
	{
		

		$response =(new School())->getSchoolDataYearRanges();
			return response()->json($response,200);
		
		
	}
	
	function deleteSchool(Request $request, $schoolCode)
	{
		$response =(new School())->deleteSchool($schoolCode);
		return response()->json($response,200);
				
	}
	
	function getSchool(Request $request, $schoolCode)
	{
		$response =(new School($schoolCode));
		return response()->json($response,200);
				
	}
}
