<?php

class appuser_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul           = 'appuser';
        $mapperClassName = 'Model_App_UserMapper';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 20
        ));
    }

    //================================================================================
    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    protected function obslugaFormularza(Core_Request $request)
    {
        $response = parent::obslugaFormularza($request);

        $rekord   = $this->getRecord();
        $rekordId = $this->getRecordPrimaryKeyValue($rekord);

        $supervisorMapper = new Model_App_UserMapper();
        $supervisorMapper->filterBy('id', $rekordId, '!=');
        $response->dodajParametr('supervisors', $supervisorMapper->find());

        $response->dodajParametr('connectedTags', Model_Tag_Service::getTags($rekord->tagIds));
        $response->dodajParametr('availableTags', Model_Tag_Service::getTagsNotInList($rekord->tagIds));

        return $response;
    }

    public function indexAction(Core_Request $request)
    {
        $response = parent::indexAction($request);

        $tagMapper = new Model_Tag_TagMapper();
        $tags = $tagMapper->find();
        $aTags = array();
        foreach($tags as $tag) {
            $aTags[$tag->id] = $tag;
        }
        $response->dodajParametr('aTags', $aTags);

        return $response;
    }
}
