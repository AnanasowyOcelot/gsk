<?php

class Model_App_UserMapper extends Core_Mapper
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getTable()
	{
		return 'app_users';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDataObjectClass()
	{
		return 'Model_App_UserEntity';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDescription()
	{
		return array(
			'id'               => array('id', Core_Mapper::T_INT),
			'name'             => array('name', Core_Mapper::T_VARCHAR),
			'email'            => array('email', Core_Mapper::T_VARCHAR),
			'password'         => array('password', Core_Mapper::T_VARCHAR),
			'supervisor_id'    => array('supervisor_id', Core_Mapper::T_INT),
			'active'           => array('active', Core_Mapper::T_INT),
			'dataUtworzenia'   => array('data_utworzenia', Core_Mapper::T_DATETIME_CREATED),
			'dataAktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME_UPDATED)
		);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	protected function buildObject(array $tableRow)
	{
		$o         = parent::buildObject($tableRow);
		$o->tagIds = Model_Tag_Service::getTagIdsForObject($o);
		$o->setTags(Model_Tag_Service::getTags($o->tagIds));
		return $o;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function save(Model_App_UserEntity $o)
	{
		parent::save($o);
		Model_Tag_Service::saveTagIdsForObject($o, $o->tagIds);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	/**
	 * @param array $r
	 * @param null|Model_App_UserEntity $object
	 * @return Model_App_UserEntity
	 */
	public function fromArray(array $r, Model_App_UserEntity $object = null)
	{
		$object = parent::fromArray($r, $object);
		if (isset($r['tag'])) {
			$object->tagIds = array();
			foreach ($r['tag'] as $tagId => $isConnected) {
				if ((int)$isConnected > 0) {
					$object->tagIds[] = $tagId;
				}
			}
		}
		return $object;
	}
}
