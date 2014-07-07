<?php

class tag_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul           = 'tag';
        $mapperClassName = 'Model_Tag_TagMapper';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 20
        ));
    }

	/**
	 * @param Core_Request $request
	 * @return Core_Response
	 */
	protected function obslugaFormularza(Core_Request $request)
	{
		$response = parent::obslugaFormularza($request);

		$categoryMapper = new Model_Tag_CategoryMapper();
		$response->dodajParametr('tagsCategories', $categoryMapper->find());

		return $response;
	}
}
/*
class tag_Controller extends Core_CMS_DoctrineModule_Controller
{
	public function __construct()
	{
		$modul           = 'tag';
		$entityName = 'Model_Entity_Tag';
		parent::__construct($modul, $entityName, array(
			'liczbaNaStrone' => 20
		));
	}
}
*/
