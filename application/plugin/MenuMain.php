<?php

class Plugin_MenuMain
{
	private $v_modul;
	private $v_akcja;

	public function render($rootId) {
		$html = '';

		$db = Core_DB::instancja();
		$rekordy = $db->Execute('SELECT
                p.podstrona_id AS id,
                p.podstrona_id_nadrzedna AS parent_id,
                po.podstrona_nazwa AS nazwa,
                po.podstrona_modul AS modul
            FROM podstrony p, podstrony_opisy po
            WHERE
                p.podstrona_id = po.podstrona_id
                AND po.jezyk_id = 1
                AND podstrona_aktywna = 1
                ORDER BY podstrona_miejsce ASC
            ')->GetRows();

		$a_drzewka = $this->buildTree($rekordy);

		foreach($a_drzewka as $a_drzewko) {
			if($a_drzewko->id == $rootId) {
				if(isset($a_drzewko->children)) {
					foreach($a_drzewko->children as $wezel) {
						$html .= $this->wyswietlDrzewo($wezel);
					}
				}
			}
		}

		return $html;
	}

	private function wyswietlDrzewo($o_wezelGlowny, $level = 0, $numer = 0) {
		$html = '';

		$class = '';
		if($numer == 0) {
			$class = 'first';
		}

		$a_wezel = $this->wyswietlWezel($o_wezelGlowny);

		$html .= '<ul>';
		$html .= '<li class="'.$class.' '.$a_wezel['class'].'">';
		$html .= $a_wezel['html'];
		$html .= '</li>';
		if(isset($o_wezelGlowny->children)) {
			$drzewo = $o_wezelGlowny->children;
			if(is_array($drzewo) && count($drzewo) > 0) {
				$numer = 0;
				$level++;
				foreach($drzewo as $wezel) {
					$html .= $this->wyswietlDrzewo($wezel, $level, $numer);
					$numer ++;
				}
			}
		}
		$html .= '</ul>';

		return $html;
	}

	private function wyswietlWezel($o_wezel) {

		$menu_modul = '';
		$link = $o_wezel->id;

		$a_zwrot = array();
		$a_zwrot['html'] = '<a href="javascript:void(0);" rel="'.$link.'" modul="'.$o_wezel->modul.'" class="'.$class_active_link.'">'.$o_wezel->nazwa.'</a> ';

		$a_zwrot['class'] = $class_active;

		return $a_zwrot;
	}

	private function buildTree(array $rekordy) {
		$items = array();
		foreach($rekordy as $r) {
			$items[] = (object)$r;
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
