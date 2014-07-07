<?php

class TestTagPermissions extends UnitTestCase
{
	/**
	 * @var Model_Tag_TagMapper
	 */
	private $tagMapper = null;

/**
	 * @var Model_App_UserMapper
	 */
	private $userMapper = null;

	public function setUp() {
		$this->tagMapper = new Model_Tag_TagMapper();
		$this->userMapper = new Model_App_UserMapper();
	}

	private function getNewTag($groupId = 0)
	{
		$tag = $this->tagMapper->getNew();
		$tag->groupId = $groupId;
		return $tag;
	}

	public function testTagShouldHaveGroup()
	{
		$tag = $this->tagMapper->getNew();
		$this->assertTrue(isset($tag->groupId));
	}

	public function testProductShouldHaveTags()
	{
		$product = new Model_Produkt();
		$this->assertEqual(array(), $product->getTags());
	}

	public function testCategoryShouldHaveTags()
	{
		$category = new Model_KategoriaProdukt();
		$this->assertEqual(array(), $category->getTags());
	}

	public function testAddTagToProduct()
	{
		$tag = $this->tagMapper->getNew();
		$product = new Model_Produkt();

		$product->addTag($tag);

		$this->assertEqual(array($tag), $product->getTags());
		$this->assertTrue(in_array($tag->id, $product->tagIds));
	}

	public function testUserShouldHaveTags()
	{
		$user = $this->userMapper->getNew();
		$this->assertEqual(array(), $user->getTags());
	}

	public function testAddTagToUser()
	{
		$tag = $this->getNewTag();
		$userMapper = new Model_App_UserMapper();
		$user = $userMapper->getNew();

		$user->addTag($tag);

		$this->assertEqual(array($tag), $user->getTags());
		$this->assertTrue(in_array($tag->id, $user->tagIds));
	}

	public function testPermissionWithoutTags()
	{
		$product = new Model_Produkt();
		$user = $this->userMapper->getNew();

		$filtered = Model_Tag_PermissionsService::filter(array($product), $user);

		$this->assertEqual($filtered, array($product));
	}

	public function testPermissionDeniedWithOneTag()
	{
		$tag = $this->getNewTag();
		$product = new Model_Produkt();
		$product->addTag($tag);
		$user = $this->userMapper->getNew();

		$filtered = Model_Tag_PermissionsService::filter(array($product), $user);

		$this->assertEqual($filtered, array());
	}

	public function testPermissionAllowedWithOneTag()
	{
		$tag = $this->getNewTag();
		$product = new Model_Produkt();
		$product->addTag($tag);
		$user = $this->userMapper->getNew();
		$user->addTag($tag);

		$filtered = Model_Tag_PermissionsService::filter(array($product), $user);

		$this->assertEqual($filtered, array($product));
	}

	public function testPermissionWithManyTagsSameGroup()
	{
		$tag1 = $this->getNewTag();
		$tag2 = $this->getNewTag();
		$product = new Model_Produkt();
		$product->addTag($tag1);
		$product->addTag($tag2);
		$user = $this->userMapper->getNew();
		$user->addTag($tag1);

		$filtered = Model_Tag_PermissionsService::filter(array($product), $user);

		$this->assertEqual($filtered, array($product));
	}

	public function testPermissionWithManyGroups()
	{
		$tag1 = $this->getNewTag(1);
		$tag2 = $this->getNewTag(2);
		$product = new Model_Produkt();
		$product->addTag($tag1);
		$product->addTag($tag2);
		$user = $this->userMapper->getNew();
		$user->addTag($tag1);

		$filtered = Model_Tag_PermissionsService::filter(array($product), $user);
		$this->assertEqual($filtered, array());

		$user->addTag($tag2);
		$filtered = Model_Tag_PermissionsService::filter(array($product), $user);
		$this->assertEqual($filtered, array($product));
	}

	public function testPermissionWithManyGroupsCategory()
	{
		$tag1 = $this->getNewTag(1);
		$tag2 = $this->getNewTag(2);
		$category = new Model_KategoriaProdukt();
		$category->addTag($tag1);
		$category->addTag($tag2);
		$user = $this->userMapper->getNew();
		$user->addTag($tag1);

		$filtered = Model_Tag_PermissionsService::filter(array($category), $user);
		$this->assertEqual($filtered, array());

		$user->addTag($tag2);
		$filtered = Model_Tag_PermissionsService::filter(array($category), $user);
		$this->assertEqual($filtered, array($category));
	}
}
