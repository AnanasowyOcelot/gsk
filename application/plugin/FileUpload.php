<?php
class Plugin_FileUpload
{
	
	public function upload($o_requestIn, $wymiary, $katalog, $prefix='' ) {
		
		if(count($wymiary)>0)
		{
			$allowedExtensions = array();		
			$sizeLimit = 10 * 1024 * 1024;
			
			$uploader = new Model_qqFileUploader($allowedExtensions, $sizeLimit);
			
			$katalog_tmp = Core_Config::get('page_dir').'tmp/';
			$result = $uploader->handleUpload($katalog_tmp);
			$test =  htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			
			
			$path = $katalog_tmp.$o_requestIn->getParametr('qqfile');	
			
		
			foreach ($wymiary as $index => $dane)
			{
				$katalog_upload = Core_Config::get('page_dir').$katalog.$index.'/';
				
				if(!file_exists($katalog_upload))
				{
					mkdir($katalog_upload,0777);
				}
				$sciezka = $katalog_upload.$prefix.'_'.$o_requestIn->getParametr('qqfile');	
				
				
				
				$szerokosc = $dane['szerokosc'];
				$wysokosc = $dane['wysokosc'];
					
				if($szerokosc=='' && $wysokosc== '')	
				{
					$a_wymiary = getimagesize($path);
					if(count($a_wymiary)>0)
					{
						$szerokosc = $a_wymiary[0];
						$wysokosc = $a_wymiary[1];
					}
				}
				$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $szerokosc, $wysokosc);
			}
		}
				
		return $test;
	}
	
	//======================================================================================
	public function uploadNew($plik_upload, $plik_save, $wymiary, $katalog ) {
		
		if(count($wymiary)>0)
		{
			$allowedExtensions = array();		
			$sizeLimit = 10 * 1024 * 1024;
			
			$uploader = new Model_qqFileUploader($allowedExtensions, $sizeLimit);
			
			$katalog_tmp = Core_Config::get('page_dir').'tmp/';
			$result = $uploader->handleUpload($katalog_tmp);
			$test =  htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			
			
			$path = $katalog_tmp.$plik_upload;	
			
		
			foreach ($wymiary as $index => $dane)
			{
				$katalog_upload = $katalog.$index.'/';
				
				if(!file_exists($katalog_upload))
				{
					mkdir($katalog_upload,0777);
				}
				$sciezka = $katalog_upload.$plik_save;	
			
			
				if($dane['typ']=='kadr')
				{
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke_kadrujac_zdjecie($path, $sciezka, $dane['szerokosc'], $dane['wysokosc'] );	
				}
				else 
				{
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $dane['szerokosc'], $dane['wysokosc'] );							
				}
				
			}
		}
				
		return $test;
	}
	
}
