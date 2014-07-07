<?php
class Plugin_MenuCMS
{
	private $v_modul;
	private $v_akcja;
	private $o_requestLocal;
	
	public function __construct($o_requestIn)
	{
		$this->o_requestLocal = $o_requestIn;	
	}
	public function render($moul='',$akcja='') {
		$html = '';
		$this->v_modul = $moul;
		$this->v_akcja = $akcja;
		$db = Core_DB::instancja();
		$rekordy = $db->Execute('SELECT * FROM nawigacja WHERE aktywny = 1 ORDER BY miejsce')->GetRows();
		$a_drzewka = $this->buildTree($rekordy);
		$level = 0;
		foreach($a_drzewka as $a_drzewko) {
			$html .= $this->wyswietlDrzewo($a_drzewko,$level);
		}
		return $html;
	}
	private function wyswietlDrzewo($o_wezelGlowny,$level) {
		$html = '';
		$class = "row_menu";
		if($level>0)
		{
			$class = "second";
		}
		$a_wezel = $this->wyswietlWezel($o_wezelGlowny);
		$html .= '<ul>';
		$html .= '<li class="'.$class.' '.$a_wezel['class'].'">';
		$html .= $a_wezel['html'];
		$html .= '</li>';
		if(isset($o_wezelGlowny->children)) {
			$drzewo = $o_wezelGlowny->children;
			if(is_array($drzewo) && count($drzewo) > 0) {
				$level++;
				foreach($drzewo as $wezel) {
					//$html .= '<ul>';
					$html .= $this->wyswietlDrzewo($wezel,$level);
					//$html .= '</ul>';
				}
			}
		}
		$html .= '</ul>';
		return $html;
	}
	private function wyswietlWezel($o_wezel) {
		$menu_modul = '';
		$link = '';
		if($o_wezel->modul != '') {
			$link = Core_Config::get('cms_dir').'/'.$o_wezel->modul.'';
			$menu_modul = $o_wezel->modul;
			if($o_wezel->akcja != '') {
				$link .= '/'.$o_wezel->akcja.'';
			}
		} elseif($o_wezel->url != '') {
			$menu_modul = $o_wezel->url;
			$link = Core_Config::get('cms_dir').'/'.$o_wezel->url.'';
		}
		$class_active = '';
		$class_active_link = '';
		if($menu_modul == $this->v_modul)
		{
			if($o_wezel->akcja != '') {
				if($o_wezel->akcja==$this->v_akcja){
					$class_active_link = 'active_href';
					$class_active = 'selected';
				}
			}
			else
			{
				//if($this->v_akcja=='index')
				{
					$class_active_link = 'active_href';
					$class_active = 'selected';
				}
			}
		}
		$a_zwrot = array();
		if($link != '') {
			$a_zwrot['html'] = '<a href="'.$link.'" class="'.$class_active_link.'">'.$o_wezel->nazwa.'</a> ';
		} else {
			$a_zwrot['html'] = '<span class="'.$class_active_link.'">'.$o_wezel->nazwa.'</span> ';
		}
		$a_zwrot['class'] = $class_active;
		//return '<a href="'.$link.'" class="'.$class_active.'">'.$o_wezel->nazwa.'</a> ';
		return $a_zwrot;
	}
	private function buildTree(array $rekordy) {
		$items = array();
		
		$uprawnienia = $this->o_requestLocal->getUprawnienia();// $_SESSION['admin_uprawnienia'];
		
		//Core_Narzedzia::drukuj($uprawnienia);
		
		foreach($rekordy as $r) {
		
			if($r['parent_id']>0)	
			{
				if(isset($uprawnienia[$r['modul']]) && $uprawnienia[$r['modul']]['przegladanie']==1)
				{
					$items[] = (object)$r;
				}
			}
			else 
			{
				$items[] = (object)$r;
			}
		}
		$childs = array();
		foreach($items as $item) {
			$childs[$item->parent_id][] = $item;
		}
		foreach($items as $item) if (isset($childs[$item->id])) {
			$item->children = $childs[$item->id];
		}
		$tree = $childs[0];
		return $tree;
	}
}
