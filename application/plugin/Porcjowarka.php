<?php

class Plugin_Porcjowarka
{


	private $ilosc_rekordow;
	private $na_strone;
	private $link;
	private $dodatkowe_parametry;

	public function __construct($ilosc_rekordow_in, $na_strone_in, $link_in, array $dodatkowe_parametr_in=array())
	{
		$this->ilosc_rekordow = $ilosc_rekordow_in;
		$this->na_strone = $na_strone_in;
		$this->link = $link_in;
		$this->dodatkowe_parametry = $dodatkowe_parametr_in;
	}

	//=====================================================
	public function  buduj($zaznaczony, $parametr_strony='s:')
	{
		$porcjowarka = '';

		if($this->ilosc_rekordow>0)
		{
			$ilosc_stron = ceil($this->ilosc_rekordow / $this->na_strone);

			if($ilosc_stron>1)
			{				
				$v_parametry_link = '';			
				if(count($this->dodatkowe_parametry)>0)
				{
					foreach ($this->dodatkowe_parametry as $nazawa => $wartosc)
					{
						$v_parametry_link .=','.$nazawa.':'.$wartosc;
					}
				}
				
				//=====================================
				
				$ostatnia_strona = ceil($this->ilosc_rekordow / $this->na_strone);
				if($zaznaczony > $ostatnia_strona)
				{
					$zaznaczony = $ostatnia_strona;
				}
				else if($zaznaczony == '' || $zaznaczony < 1)
				{
					$zaznaczony = 1;
				}
	
				$koncowy_element = $this->ilosc_rekordow;
				$pstrona = $zaznaczony - 1;
				$nstrona = $zaznaczony + 1;
				$strona_offset = ($zaznaczony - 1) * $this->na_strone;
	
				if($zaznaczony > 5)
				{
					$start = $zaznaczony - 4;
					$stop = $start + 9;
				}
				else
				{
					$start = 1;
					$stop = 10;
				}
	
				if($stop > $ostatnia_strona)
				{
					$stop = $ostatnia_strona;
				}
	
				if($stop - $start < 10)
				{
					$start = $stop - 9;
				}
	
				if($start < 1)
				{
					$start = 1;
				}
				//echo "====>".$start." <===> ".$stop."<==<br>";
				//=====================================
	
				if(($zaznaczony - 1) > 0) {
					$porcjowarka .= '<a href="'.$this->link.'s:1'.$v_parametry_link.'" class="left">pierwsza</a>';
					$porcjowarka .= '<span>&nbsp;</span><a href="'.$this->link.$parametr_strony.($zaznaczony - 1).''.$v_parametry_link.'">poprzednia</a>';
				} else {
					$porcjowarka .= '<a href="javascript:void(0);" class="nieaktywny left">pierwsza</a>';
					$porcjowarka .= '<span>&nbsp;</span><a href="javascript:void(0);" class="nieaktywny">poprzednia</a>';
				}
				
				
						
				//for ($x=0;$x<$ilosc_stron;$x++)
				for($x = $start; $x <= $stop; $x++)
				{
					$strona = $x;//+1;
					
					$link = $this->link.$parametr_strony.$strona.$v_parametry_link;
	
					if($zaznaczony==$strona)
					{
						$porcjowarka .= '<span class="aktywny">&nbsp;</span><a href="'.$link.'" class="aktywny">'.$strona.'</a>';
					}
					else
					{
						$porcjowarka .= '<span>&nbsp;</span><a href="'.$link.'">' .$strona.' </a>';
					}
				}
	
				if(($zaznaczony + 1) <= $ilosc_stron) {
					$porcjowarka .= '<span>&nbsp;</span><a href="'.$this->link.$parametr_strony.($zaznaczony + 1).''.$v_parametry_link.'">następna</a>';
					$porcjowarka .= '<span>&nbsp;</span><a href="'.$this->link.$parametr_strony.($ilosc_stron).''.$v_parametry_link.'" class="right">ostatnia</a>';
				} else {
					$porcjowarka .= '<span>&nbsp;</span><a href="javascript:void(0);" class="nieaktywny">następna</a>';
					$porcjowarka .= '<span>&nbsp;</span><a href="javascript:void(0);" class="nieaktywny right">ostatnia</a>';
				}
			}
		}

		return $porcjowarka;
	}
};