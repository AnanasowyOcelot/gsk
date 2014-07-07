<?php


class Controller_Router{

	public  $_loadedSection;
	public  $_dataArray;
	protected $_sectionSeparator = ':';
	protected $_nestSeparator = '.';
	protected $_extends = array();
	protected $_skipExtends = false;

	public function __construct($filename, $section = null, $options = false)
	{
		if (empty($filename)) {
			echo "ERROR";
		}

		$iniArray = $this->_loadIniFile($filename);

		if (null === $section)
		{
			$dataArray = array();
			foreach ($iniArray as $sectionName => $sectionData)
			{
				if(!is_array($sectionData))
				{
					$dataArray = $this->_arrayMergeRecursive($dataArray, $this->_processKey(array(), $sectionName, $sectionData));
				} else {
					$dataArray[$sectionName] = $this->_processSection($iniArray, $sectionName);
				}
			}
			//parent::__construct($dataArray, $allowModifications);
		} else {
			// Load one or more sections
			if (!is_array($section)) {
				$section = array($section);
			}
			$dataArray = array();
			foreach ($section as $sectionName) {
				if (!isset($iniArray[$sectionName])) {
					echo "Section '$sectionName' cannot be found in $filename";
				}
				$dataArray = $this->_arrayMergeRecursive($this->_processSection($iniArray, $sectionName), $dataArray);

			}
			//parent::__construct($dataArray, $allowModifications);
		}

		$this->_dataArray = $dataArray;
		$this->_loadedSection = $section;
	}
	//==============================================================================
	protected function _loadIniFile($filename)
	{
		$loaded = $this->_parseIniFile($filename);
		$iniArray = array();
		foreach ($loaded as $key => $data)
		{
			$pieces = explode($this->_sectionSeparator, $key);
			$thisSection = trim($pieces[0]);
			switch (count($pieces)) {
				case 1:
					$iniArray[$thisSection] = $data;
					break;

				case 2:
					$extendedSection = trim($pieces[1]);
					$iniArray[$thisSection] = array_merge(array(';extends'=>$extendedSection), $data);
					break;

				default:
					echo "Section '$thisSection' may not extend multiple sections in $filename";
			}
		}

		return $iniArray;
	}
	//==============================================================================
	protected function _assertValidExtend($extendingSection, $extendedSection)
	{
		// detect circular section inheritance
		$extendedSectionCurrent = $extendedSection;
		while (array_key_exists($extendedSectionCurrent, $this->_extends)) {
			if ($this->_extends[$extendedSectionCurrent] == $extendingSection) {
				echo 'Illegal circular inheritance detected';
			}
			$extendedSectionCurrent = $this->_extends[$extendedSectionCurrent];
		}
		// remember that this section extends another section
		$this->_extends[$extendingSection] = $extendedSection;
	}
	//==============================================================================
	protected function _processSection($iniArray, $section, $config = array())
	{
		$thisSection = $iniArray[$section];

		foreach ($thisSection as $key => $value) {
			if (strtolower($key) == ';extends') {
				if (isset($iniArray[$value])) {
					$this->_assertValidExtend($section, $value);

					if (!$this->_skipExtends) {
						$config = $this->_processSection($iniArray, $value, $config);
					}
				} else {
					echo "Parent section '$section' cannot be found";
				}
			} else {
				$config = $this->_processKey($config, $key, $value);
			}
		}
		return $config;
	}
	//==============================================================================
	protected function _processKey($config, $key, $value)
	{
		if (strpos($key, $this->_nestSeparator) !== false) {
			$pieces = explode($this->_nestSeparator, $key, 2);
			if (strlen($pieces[0]) && strlen($pieces[1])) {
				if (!isset($config[$pieces[0]])) {
					if ($pieces[0] === '0' && !empty($config)) {
						// convert the current values in $config into an array
						$config = array($pieces[0] => $config);
					} else {
						$config[$pieces[0]] = array();
					}
				} elseif (!is_array($config[$pieces[0]])) {

					echo "Cannot create sub-key for '{$pieces[0]}' as key already exists";
				}
				$config[$pieces[0]] = $this->_processKey($config[$pieces[0]], $pieces[1], $value);
			} else {
				echo "Invalid key '$key'";
			}
		} else {
			$config[$key] = $value;
		}
		return $config;
	}
	//==============================================================================
	protected function _arrayMergeRecursive($firstArray, $secondArray)
	{
		if (is_array($firstArray) && is_array($secondArray)) {
			foreach ($secondArray as $key => $value) {
				if (isset($firstArray[$key])) {
					$firstArray[$key] = $this->_arrayMergeRecursive($firstArray[$key], $value);
				} else {
					if($key === 0) {
						$firstArray= array(0=>$this->_arrayMergeRecursive($firstArray, $value));
					} else {
						$firstArray[$key] = $value;
					}
				}
			}
		} else {
			$firstArray = $secondArray;
		}

		return $firstArray;
	}
	//==============================================================================
	protected function _parseIniFile($filename)
	{
		$iniArray = parse_ini_file($filename, true);
		return $iniArray;
	}

}