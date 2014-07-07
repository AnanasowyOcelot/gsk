<?php
class box_View extends Core_View
{
	public function __construct() {
		$this->modul = 'box';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}

	//============================================================================
	function boxPodstronaWidok($podstrona_id, $jezyk_id)
	{
		$html = '';
		if((int)$podstrona_id>0)
		{
			$o_box_s = new Model_Box();
			$o_box_s->filtr_jezyk_id = $jezyk_id;
			$o_box_s->podstrona_id[] = $podstrona_id;
			$o_box_s->filtrujRekordy();


			$a_naglowki = array();

			$a_naglowki[1]['link_more'] = "więcej";
			$a_naglowki[1]['wypelnij_formularz'] = "Wypełnij formularz";
			$a_naglowki[2]['link_more'] = "more";
			$a_naglowki[2]['wypelnij_formularz'] = "Send a Query";


			$this->sm->assign('jezyk_id', $jezyk_id);
			$this->sm->assign('naglowki', $a_naglowki);

			foreach ($o_box_s->rekordy as $index => $box_id)
			{
				$o_box = new Model_Box($box_id);
				if($o_box->aktywna[$jezyk_id] == 1)
				{
					$this->sm->assign('box', $o_box);
					$tpl = "box";

					if($o_box->szablon_id!="")
					{
						$tpl = $o_box->szablon_id;
					}
					$html .=  $this->sm->fetch($this->moduleTemplateDir.$tpl.'.tpl');
				}

			}



		}


		return $html;
	}
	//============================================================================
	function boxWidok($rekord_id, $jezyk_id)
	{
		$html = '';
		if((int)$rekord_id>0)
		{
			$o_box = new Model_Box($rekord_id);
			$a_naglowki = array();
			$a_naglowki[1]['link_more'] = "więcej";
			$a_naglowki[1]['wypelnij_formularz'] = "Wypełnij formularz";
			$a_naglowki[2]['link_more'] = "more";
			$a_naglowki[2]['wypelnij_formularz'] = "Send a Query";

			if($o_box->aktywna[$jezyk_id] == 1)
			{
				$this->sm->assign('box', $o_box);
				$this->sm->assign('jezyk_id', $jezyk_id);
				$this->sm->assign('naglowki', $a_naglowki);

				$tpl = "box";

				if($o_box->szablon_id!="")
				{
					$tpl = $o_box->szablon_id;
				}
				$html .=  $this->sm->fetch($this->moduleTemplateDir.$tpl.'.tpl');
			}
		}


		return $html;
	}
	//============================================================================
	function boxAnimacja($rekord_id, $jezyk_id)
	{
		$html = '';
		$content = '';
		if((int)$rekord_id>0)
		{
			$o_gal = new Model_Galeria($rekord_id);			
			
			
			$a_elementy = array();
			
			$content = '<ul id="boxAnimacja" style="width:245px;">';
			foreach ($o_gal->zdjecia as $index => $sciezka)
			{
				$a_elementy[] = ' "/images/galerie/0/'.$sciezka.'" : "" ';
				
				//$content .= "=>".$o_gal->foto_nazwy[$index][$jezyk_id];
				
				$content .='
				<li>
					<div class="" style="height:190px; overflow:hidden; text-align:center;">					
						<img src="/images/galerie/4/'.$sciezka.'" >					
					</div>
					<div style="font-weight:bold;width:245px;">'.$o_gal->foto_nazwy[$index][$jezyk_id].'</div>
				</li>
				';
			}
			
			$content .="</ul>";
			
			$content .='<script type="text/javascript">

			jQuery(document).ready(function () {			            
			
				jQuery("#boxAnimacja").cycle({
					fx: "fade",
					speed:       200
					});
				});
			
				</script>
				';
			
		
		}
		
		$a_naglowki[1]['tytul'] = "Broń na strzelincy";			
		$a_naglowki[2]['tytul'] = "Our firearms";

		$this->sm->assign('tresc', $content);
		$this->sm->assign('naglowki', $a_naglowki);
		$this->sm->assign('jezyk_id', $jezyk_id);
		
		$tpl = "bron_strzelinca_galeria";
	
		
		$html .=  $this->sm->fetch($this->moduleTemplateDir.$tpl.'.tpl');

		return $html;
	}
	
	
};
