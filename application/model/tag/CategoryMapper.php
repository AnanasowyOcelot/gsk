<?php

class Model_Tag_CategoryMapper extends Core_Mapper
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getTable()
	{
		return 'tags_categories';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDataObjectClass()
	{
		return 'Model_Tag_CategoryEntity';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDescription()
	{
		return array(
			'id' => array('id', Core_Mapper::T_INT),
			'name' => array('name', Core_Mapper::T_VARCHAR)
		);
	}
}
