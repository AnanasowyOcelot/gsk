<?php

class Model_AsortymentSieci_KlientEntity
{
	public $id = 0;
	public $nazwa = '';

	private $_postedFiles = null;

	public function getPostedFiles()
	{
		return $this->_postedFiles;
	}

	public function setPostedFiles($postedFiles)
	{
		$this->_postedFiles = $postedFiles;
	}


}
