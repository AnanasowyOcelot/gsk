<?php

class Model_SubskrybentMapper extends Core_Mapper
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getTable()
	{
		return 'subskrybenci';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDataObjectClass()
	{
		return 'Model_Subskrybent';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDescription()
	{
		return array(
			'id'           => array('s_id', Core_Mapper::T_INT),
			'email'        => array('s_email', Core_Mapper::T_VARCHAR),
			'data_dodania' => array('s_data_dodania', Core_Mapper::T_VARCHAR),
			'ip'           => array('s_ip', Core_Mapper::T_VARCHAR),
			'aktywny'      => array('s_aktywny', Core_Mapper::T_INT)
		);
	}
}
