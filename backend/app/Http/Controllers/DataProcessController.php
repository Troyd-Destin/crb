<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProcess;
use App\Models\School;

class DataProcessController extends Controller
{
    //
	function translateSchool(Request $request, $schoolCode)
	{
			$school =(new DataProcess())->fetchSchools($schoolCode);
			//dd($school);
			$schoolLocation =(new DataProcess())->fetchCoordsForAddress($school->school_city.' '.$school->school_postal_code.'  '.$school->school_address1.' '.$school->school_address2);
			
			foreach($schoolLocation as $key => $value)
			{
				$school->$key = $value;
				
			}
			//dd($schoolLocation);
			(new DataProcess())->updateLongLatSchool($school->school_code,$schoolLocation);
			
			$school = (new School($schoolCode));
			
			return response()->json($school,200);
		
	}
	
	function updateSchool (Request $request,$schoolCode){
		
		$input = $request->all();
		
				
		$response = (new DataProcess())->updateLongLatSchool($schoolCode,$input);
		
		return response()->json($response,200);
	}
	
	function translateAllSchools(Request $request)
	{
		set_time_limit ( 0 );
		
		$schools =(new DataProcess())->fetchSchools();
		//dd($schools);
		foreach($schools as $school)
		{
			//dd($school);
			$schoolLocation =(new DataProcess())->fetchCoordsForAddress($school->school_address1.' '.$school->school_city.' '.$school->school_postal_code);
			
			//dd($schoolLocation,$school);
			if($schoolLocation)
			{
				(new DataProcess())->updateLongLatSchool($school->school_code,$schoolLocation);
			}
			//if($school->school_code == "9364") dd($school,schoolLocation);
		}
		
		
		return response()->json(array('succes' => 'Update Complete'),200);
		//dd($schools);
		
		
	}
	
	function processEducationAlbertaDownloads (Request $request)
	{
		$response =(new DataProcess())->processEducationAlbertaDownloads();
		return response()->json($response,200);
		
		
	}
	
	function processEducationAlbertaSchoolIndexDownload (Request $request)
	{
		$response =(new DataProcess())->processEducationAlbertaSchoolIndexDownload();
		$response =(new School())->missingGeocode();
		
		return response()->json($response,200);
		
		
	}
}
