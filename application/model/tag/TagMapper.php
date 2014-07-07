<?php

class Model_Tag_TagMapper extends Core_Mapper
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getTable()
	{
		return 'tags';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDataObjectClass()
	{
		return 'Model_Tag_TagEntity';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDescription()
	{
		return array(
			'id' => array('id', Core_Mapper::T_INT),
			'name' => array('name', Core_Mapper::T_VARCHAR),
			'description' => array('description', Core_Mapper::T_TEXT),
			'color' => array('color', Core_Mapper::T_VARCHAR),
			'groupId' => array('category_id', Core_Mapper::T_INT)
		);
	}
}
