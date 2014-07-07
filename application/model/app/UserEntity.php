<?php

class Model_App_UserEntity
{
	public $id = 0;
	public $name = '';
	public $email = '';
	public $password = '';
	public $supervisor_id = 0;
	public $active = 0;
	public $dataUtworzenia = '';
	public $dataAktualizacji = '';

	public $tagIds = array();
	private $tags = array();

	/**
	 * @return Model_App_UserEntity
	 */
	public function getSupervisor()
	{
		$supervisorMapper = new Model_App_UserMapper();
		$supervisor       = $supervisorMapper->findOneById($this->supervisor_id);
		if ($supervisor !== null) {
			return $supervisor;
		} else {
			return new self();
		}
	}

	/**
	 * @return string
	 */
	public function getSupervisorName()
	{
		$supervisor = $this->getSupervisor();
		return $supervisor->name;
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function setTags(array $tags)
	{
		$this->tags = $tags;
	}

	public function addTag(Model_Tag_TagEntity $tag)
	{
		$this->tags[]   = $tag;
		$this->tagIds[] = $tag->id;
	}
}
