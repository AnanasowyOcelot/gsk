<?php
class video_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'video';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	//============================================================================
	function wyswietl($jezyk_id, Model_Video $o_video, $linkDoVideo = '') {
		
		$html = '';
		$this->sm->assign('video', $o_video);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('link_powrot', $linkDoVideo);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'szczegoly.tpl');
		return $html;
	}
	
	//============================================================================
	function BudujListeStronaGlowna($jezyk_id,$strona) {
		
		$html = '';
		
		$na_strone = 15;
		$o_video = new Model_Video();
		$o_video->filtr_jezyk_id = $jezyk_id;
		$o_video->filtr_ilosc_wynikow = $na_strone;
		$o_video->filtr_strona = $strona;
		$o_video->filtr_sortuj_jak = "DESC";
        		$o_video->filtr_sortuj_po = "data_wydarzenia";
		
		$o_video->filtr_aktywna = 1;
		$o_video->filtrujRekordy();
		
		
		foreach($o_video->rekordy as $index =>$video_id)
		{	
			$video = new Model_Video($video_id);						
			$a_video[$video_id] = $video;
		}
		
		/*
		$link = '/pl/video/';
		$a_parametry = array();
		$parametry_strony = '';
		$o_porcjowarka = new Plugin_Porcjowarka($o_video->ilosc_rekordow, $na_strone, $link, $a_parametry);
		$porcjowarka = $o_porcjowarka->buduj($strona,$parametry_strony);
		
		$this->sm->assign('video', $a_video);
		$this->sm->assign('porcjowarka', $porcjowarka);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'lista_strona_glowna.tpl');	
		*/
		
		$html = '';
		$nawigacja = '';
		$strony = 1;
		
		
		
		
		$html = '<ul id="videoGlowna" class="jcarousel-skin-tango">';
		foreach ($a_video as $index => $video)
		{
			
			$html .='<li class="videoElement">';
				$html .= '<div class="film"><iframe width="270" height="165" src="'.$video->film_youtube.'?wmode=opaque" frameborder="0" allowfullscreen id="ekran_'.$video->id.'" wmode="opaque"></iframe></div>';
				$html .= '<div class="videoTytul">'.$video->nazwa[$jezyk_id].'</div>';
				$html .= '<div class="videoOpis">'.$video->tresc[$jezyk_id].'</div>';			
			$html .='</li>';
			
			$class="";
			if($strony==1)
			{
				$class="selected";	
			}
			$nawigacja .='<a href="#" class="'.$class.'"  alt="'.$strony.'" ></a>';
			$strony++;
		}
		$html .= '</ul>';
		
		$html_nawigacja = '<div class="jcarousel-control" id="jcontrol">                             
                                		<div class="nav">'.$nawigacja.'</div>
                    		</div>';
		
		$widok ='<div style="width:288px; height:214px; display:block; float:left; margin-left:8px; " >';
		$widok .='	<div class="nawigacjaRow">'.$html_nawigacja.'</div>';
		$widok .='	<div class="videoRow">'.$html.'</div>';
		$widok .='</div>';

		
		return $widok;	
	}
	//============================================================================
	
};
