<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //

	function __construct($schoolCode = null)
	{
		
		if(!$schoolCode)
		{
			$schoolProperties = DB::select('DESCRIBE data_schools')	;
			foreach($schoolProperties as $obj)
			{
			//	dd($obj);
				$this->{$obj->Field} = null;
				
			}
			$this->enrollment = [];
		}
		else
		{
			$temp = $this->getSchoolProperties($schoolCode);
			$temp->enrolment = $this->getSchoolEnrolment($schoolCode);
			foreach($temp as $key => $value)
			{
			//	dd($obj);
				$this->{$key} = $value;
				
			}
			// do that shit properly
			
			return $this;
		}
		
		
	}
	
	function deleteSchool($schoolCode){
		
		$query = DB::table('data_schools')
		->where('school_code','=',$schoolCode)
		->delete();
		
		return $query;
		
		
		
	}
	
	function getSchoolEnrolment ($schoolCode)
	{
		
		$query = DB::table('data_schools_population')
		->where('school_code','=',$schoolCode)
		->get();
		
		return $query;
		
	}
	
	function getSchoolProperties ($schoolCode)
	{
		
		$query = DB::table('data_schools')
		->where('school_code','=',$schoolCode)
		->first();
		
		return $query;
	}	
	
	function getSchoolDataYearRanges ()
	{
		
		$responseArray = [];
		
		$responseArray['enrolment'] = DB::table('data_schools_population')
		->groupBy('start_year')
		->pluck('start_year');
		//dd('test');
		
		
		return $responseArray;
		
		
	}
	
	function getAllowedSchoolsArray ()
	{
		$query = DB::table('data_schools')
		->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})
		->pluck('school_code');
		
		return $query->toArray();
	}
	
	function missingGeocode ()
	{
		$query = DB::table('data_schools')
		->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})
		->whereNull('latitude')
		->get();
		
		return $query;
		
		
	}
}
