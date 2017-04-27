<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB; 
use Excel;
use Storage;

class DataRetrieval extends Model
{
    //
	
	function fetchSchools($schoolCode = null,$input)
	{
		
		$returnArray = [];
		
		isset($input['limit']) ? $limit = $input['limit'] : $limit = 100;
		isset($input['page']) ? $page = $input['page'] : $page = 1;
		
		
		
		//$totalRecords = DB::table('data_schools')->count();
		$totalRecords = DB::table('data_schools')->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})->count();
	
		
		$skip = ($page - 1) * $limit;
		
	//	dd($skip);
	
		$query = DB::table('data_schools')
		->select('*')
		->take($limit)
		->skip($skip)
		->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			});
		
		if(isset($schoolCode))
		{
			$query = $query->where('school_code','=',$schoolCode)->first();
			
		}
		
	
		
		$query = $query->get();
			
		//	dd($query);
			
			
			foreach($query as $school)
			{
				
				$school->lnglat = $school->longitude.",".$school->latitude;
			}
			
			$returnArray['totalRecords']= $totalRecords;
			$returnArray['limit'] = $limit;
			$returnArray['page'] = $page;
			$returnArray['maxPage'] = ceil($totalRecords/$limit);
			$returnArray['results'] = $query;
		
		
		
		
		
		
		return $returnArray;
		
	}
	
	
	
	function exportDataSchoolPopulation($year = 2015)
	{
		
		$filename = 'crb_school_population_export_'.$year;
		
		$data = DB::table('data_schools')
		->join('data_schools_population','data_schools_population.school_code','=','data_schools.school_code')
		->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})
		->where('data_schools_population.start_year','=',$year)
		->get();
	
		
		$excel = Excel::create($filename, function($excel) use($data) {

		
				$excel->sheet('Sheetname', function($sheet) use ($data) {

				
					$rowCount = 1;
					foreach($data as $key => $value)
					{
						
						if($key == 0)
						{
							$colCount = 'a';
							
							foreach($value as $colName => $colValue)
							{
							$sheet->cell($colCount.$rowCount, function($cell) use ($colName) { $cell->setValue($colName); });
							$colCount++;
							}
							
							$rowCount++;
							
						}
						$colCount = 'a';
						foreach($value as $colName => $colValue)
							{
								$sheet->cell($colCount.$rowCount, function($cell) use ($colValue) { $cell->setValue($colValue); });
								$colCount++;
							}
					
						$rowCount++;
					}
					// Sheet manipulation

				});

			})->store('csv','exports');
			
		return '/exports/'.$filename.'.csv';
	
		
	
		
	}
	
	function exportDataSchoolPopulationDelta($startYear = 2012,$endYear = 2013)
	{
		
		$responseArray = [];
		
		$filename = 'crb_school_delta_population_export_'.$endYear.'_minus_'.$startYear;
		
		$startYearData = DB::table('data_schools')
		->join('data_schools_population','data_schools_population.school_code','=','data_schools.school_code')
		->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})
		->where('data_schools_population.start_year','=',$startYear)
		->get();
		
		foreach($startYearData as $school)
		{
			
			$easyAcessStartYear[$school->school_code] = $school;
			
		}
		
		
		$endYearData = DB::table('data_schools')
		->join('data_schools_population','data_schools_population.school_code','=','data_schools.school_code')
		->whereIn('school_city', function($query)
			{
				$query->select('city_name')
					  ->from('crb_cities');
					 
			})
			
		->whereIn('data_schools.school_code', function($query) use ($startYear)
			{
				$query->select('school_code')
					  ->from('data_schools_population')
					  ->where('start_year','=',$startYear);
					 
			})
		->where('data_schools_population.start_year','=',$endYear)
		->get();
	
	foreach($endYearData as $school)
	{
		
		
		if(isset($easyAcessStartYear[$school->school_code]))
		{
			$startYearSchool = $easyAcessStartYear[$school->school_code];
			foreach($startYearSchool as $key =>$value)
			{
				if(is_numeric($value) && !in_array($key,['school_code','start_year','end_year','latitude','longitude','school_authority_code']))
				{
					$school->{$key} = $school->{$key} - $value;
				}
				
				
			}
		}
		
		$school->end_year = $endYear;
		$school->start_year = $startYear;
		
		$responseArray[] = $school;
		
	}
		
		
		
	 $excel = Excel::create($filename, function($excel) use($responseArray) {

		
				$excel->sheet('Sheetname', function($sheet) use ($responseArray) {

				
					$rowCount = 1;
					foreach($responseArray as $key => $value)
					{
						
						if($key == 0)
						{
							$colCount = 'a';
							
							foreach($value as $colName => $colValue)
							{
							$sheet->cell($colCount.$rowCount, function($cell) use ($colName) { $cell->setValue($colName); });
							$colCount++;
							}
							
							$rowCount++;
							
						}
						$colCount = 'a';
						foreach($value as $colName => $colValue)
							{
								$sheet->cell($colCount.$rowCount, function($cell) use ($colValue) { $cell->setValue($colValue); });
								$colCount++;
							}
					
						$rowCount++;
					}
					// Sheet manipulation

				});

			})->store('csv','exports');
			
		return '/exports/'.$filename.'.csv';
	
		
	
		
	}
	
	
	function fetchSchoolIndexAlbertaEducation ()
	{
		
		$apiUrl = 'https://education.alberta.ca/api/Topic/GetTopicDetailsViewModel/?currentNodeId=46249';
		$file = $this->download_file_behind_ssl($apiUrl);
		$json = json_decode($file, true);
		
		
		$files = Storage::disk('school-index')->files();
		
		foreach($files as $fileName)
		{
			Storage::disk('school-index')->delete($fileName);
			
			
		}
	
		
		 	foreach($json['SelectedTopic']['SubTopics'] as $subTopic)
			{
				if($subTopic['FullUrl'] == 'http://education.alberta.ca/alberta-education/school-authority-index/')
				{
				
					foreach($subTopic['ToolboxItems'] as $toolBoxItem)
					{
						if($toolBoxItem['FullUrl'] == 'http://education.alberta.ca/alberta-education/school-authority-index/everyone/school-authority-information-reports/')
						{
							foreach($toolBoxItem['ResourceList']['FolderlessResourceFiles'] as $file)
							{
									$fileUrl = "http://education.alberta.ca/".$file['FilePath'];
									$downloadedFile = $this->download_file_behind_ssl($fileUrl);
									$fileName = "education-alberta/school-index/".str_replace(' ', '_', $file['Title']).'.'.$file['Extension'];
									//dd($fileName);
									Storage::put($fileName, $downloadedFile);
								
							}
							
						}
						
						
					}
					
				}
				
				
			} 
			
		//Fix Authorities & Schools
		$disk = Storage::disk('school-index')->getDriver()->getAdapter()->getPathPrefix();
		$fileName = 'Authorities_and_Schools_Index.xlsx';
		$baseName = basename($fileName);
		$filePath = str_replace('/', '\\', $disk.$fileName);
			
		Excel::load($filePath, function($reader) use (&$excel){
			
		   $objExcel = $reader->getExcel();
			$sheet = $objExcel->getSheet(0);
			$sheet->unmergeCells('A1:C1');
			$sheet->removeRow(1);
			$sheet->removeRow(1);
			
			
				
			})->store('xlsx',$disk);
			
			return ['success' => true];
		
	}
	
	
	function fetchSchoolEnrolmentDataAlbertaEducation ()
	{
		
		/*

		url https://education.alberta.ca/alberta-education/student-population/everyone/school-authority-enrolment-data/
		topic ID 46078 // corresponds to the enrolment data
		
		
		// the Alberta Education API needs this url 


		*/
		$apiUrl = 'https://education.alberta.ca/api/Topic/GetTopicDetailsViewModel/?currentNodeId=46078';
		$file = $this->download_file_behind_ssl($apiUrl);
		$json = json_decode($file, true);
		
		//Storage::disk('enrollment')->deleteDirectory();
		$files = Storage::disk('enrollment')->files();
		
		foreach($files as $fileName)
		{
			Storage::disk('enrollment')->delete($fileName);
			
			
		}
	
		
		 	foreach($json['SelectedTopic']['SubTopics'] as $subTopic)
			{
				if($subTopic['FullUrl'] == 'http://education.alberta.ca/alberta-education/student-population/')
				{
					
					foreach($subTopic['ToolboxItems'] as $toolBoxItem)
					{
						if($toolBoxItem['FullUrl'] == 'http://education.alberta.ca/alberta-education/student-population/everyone/school-authority-enrolment-data/')
						{
							foreach($toolBoxItem['ResourceList']['FolderlessResourceFiles'] as $file)
							{
						
							
								if(strpos($file['Title'],'School Enrolment') > -1) // We're only interested in School Enrolment. 
								{
									$fileUrl = "http://education.alberta.ca/".$file['FilePath'];
									$downloadedFile = $this->download_file_behind_ssl($fileUrl);
									$fileName = "education-alberta/student-enrollment/".str_replace('/', '-', $file['Title']).'.'.$file['Extension'];
									Storage::put($fileName, $downloadedFile);
								}
							}
							
						}
						
						
					}
					
				}
				
				
			} 
			
			return ['success' => true];
	}

	
	function download_file_behind_ssl($url)
	{
			
		  $ch = curl_init();

			curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);     
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);		//ignore SSL requirements.	

			$data = curl_exec($ch);

			$out = curl_close($ch);
				
			
			return $data;
			
		
	}
}
