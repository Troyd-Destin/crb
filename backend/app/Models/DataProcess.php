<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\Models\DataRetrieval;
use App\Models\School;
use Excel;
use Storage;
use Vendor\phpoffice\phpexcel\Classes\PHPExcel;

class DataProcess extends Model
{
    //
	function __construct(){
		
		return;
		
	}
	
	function fetchCoordsForAddress ($address)
	{
		$array = array();
		$address = str_replace (" ", "+", urlencode($address));
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".getenv('GOOGLE_API_KEY');
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($ch), true);

		
	   if($response['results'])
	   {
		//get the first search result
		$array = array(
			'latitude' => $response['results'][0]['geometry']['location']['lat'],
			'longitude' => $response['results'][0]['geometry']['location']['lng'],
		);
	   }
	  
		return $array;
		
		
	}
	
	function updateLongLatSchool($schoolCode,$input)
	{
		
		$whitelist = ['longitude','latitude','authority_type','school_phone','school_postal_code','school_province','school_city','school_address2','school_address1','school_name'];
		$input = array_intersect_key($input, array_flip($whitelist));	

		$query = DB::table('data_schools')
		
		->where('school_code','=',$schoolCode)
		->update($input);
		
		if(!$query) return false;
		
		return $this->fetchSchools($schoolCode);

	}
	
	function fetchSchools($schoolCode = null)
	{
		$query = DB::table('data_schools')
		->select('*');
		
		if(isset($schoolCode))
		{
			$query = $query->where('school_code','=',$schoolCode)->first();
			
		}
		else
		{		
			$query = $query
			->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})
			->where(function($query)
			{
				$query->orWhere('longitude','>','-110')
				->orWhereNull('longitude');
					
			})
			->get();
		
			//dd($query);
		}
		return $query;
		
	}
	
		
	function processEducationAlbertaDownloads ()
	{

		// get all files in the storage diectory
		$bad_keys = ['school_authority_name','school_name','school_authority_name','school_authority_category']; // we aren't storing these in this table.
		$disk = Storage::disk('enrollment')->getDriver()->getAdapter()->getPathPrefix();
		$schoolCodes = (new School())->getAllowedSchoolsArray();
		
		Excel::batch(rtrim($disk,"/"), function($rows, $file) use ($bad_keys,$schoolCodes) {

				
				$filePieces = explode('-',basename($file));
				$startYear = $filePieces[0];
				$filePieces = explode(' ',basename($filePieces[1]));
				$endYear = $filePieces[0];
				

				// Each row will be parsed and update the Database.
				// for every file inside the batch
				$rows->each(function($row) use ($startYear,$endYear,$bad_keys,$schoolCodes) {
				
					$update = $row->toArray();
						$update['start_year'] = $startYear;
						$update['end_year'] = $endYear;
						$update =array_diff_key($update,array_flip($bad_keys));
						$update = array_filter($update);

					if(isset($update['school_code']) && in_array($update['school_code'],$schoolCodes))
					{	

						try{
								
								$query = DB::table('data_schools_population')
								->insert($update);
								
							}
							catch(\Exception $e)
							{
								
							DB::table('data_schools_population')->where('school_code','=',$update['school_code'])
							->where('start_year','=',$update['start_year'])
							->where('end_year','=',$update['end_year'])
							->update($update);
								
							}

					}
				
				
				});

			});
		
	
		//Purge all non crb records
		
		return ['success' => true];
		
	}
	
	
	function processEducationAlbertaSchoolIndexDownload ()
	{

		// get all files in the storage diectory
		$bad_keys = ['extract_date']; // we aren't storing these in this table.
		$disk = Storage::disk('school-index')->getDriver()->getAdapter()->getPathPrefix();
		$schoolCodes = (new School())->getAllowedSchoolsArray();
		$fileName = 'Authorities_and_Schools_Index.xlsx';
		$baseName = basename($fileName);
		$filePath = str_replace('/', '\\', $disk.$fileName);
	

			
		Excel::load($filePath, function($reader) use ($bad_keys,$schoolCodes) {
			
		  
						$reader->each(function($sheet)  use ($bad_keys,$schoolCodes){
							
									$sheet->each(function($row) use ($bad_keys,$schoolCodes){
										
																			
													$update = $row->toArray();
														
														$update =array_diff_key($update,array_flip($bad_keys));
														$update = array_filter($update);
														
														foreach($update as $key => $value)
														{
															if($value == 'N') $update[$key] = false;
															if($value == 'Y') $update[$key] = true;
													
															
														}														
												 		try{
																	
																	$query = DB::table('data_schools')
																	->insert($update); 
																	
																}
																catch(\Exception $e)
																{ 
																
																	DB::table('data_schools')
																	->where('school_code','=',$update['school_code'])
																	->update($update); 
																	
																}


									});
								
						});
									
			});
			//dd(get_class_methods($first,$first);
												/* 
														
														if(isset($update['school_code']) && in_array($update['school_code'],$schoolCodes))
														{	

															try{
																	
																	$query = DB::table('data_schools_population')
																	->insert($update); 
																	
																}
																catch(\Exception $e)
																{
																	
															/* 	DB::table('data_schools_population')->where('school_code','=',$update['school_code'])
																->where('start_year','=',$update['start_year'])
																->where('end_year','=',$update['end_year'])
																->update($update); 
																	
																}

														} */
										
										
			
				
			
		
		//Purge all non crb records
		
		return ['success' => true];
		
	}
	
	
	
}
