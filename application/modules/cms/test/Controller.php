<?php

require_once Core_Config::get('libs_path') . "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


//use Zend\Form\Form;
//use Zend\Form\Element;

//use Zend\Permissions\Acl\Acl;
//use Zend\Permissions\Acl\Role\GenericRole as Role;
//use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class test_Controller extends Core_ModuleController
{
    public function __construct()
    {
        $this->modul = 'test';
        parent::__construct();
    }


    //================================================================================
    function indexAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate("zend");
        $debug = '';

        ///////////////////////////////////////////////////////////////////////////////

        $paths = array(Core_Config::get('application_path') . "model/entity");
        $isDevMode = true;
        $dbParams = array(
            'driver' => 'pdo_mysql',
            'host' => Core_Config::get('db.host'),
            'user' => Core_Config::get('db.user'),
            'password' => Core_Config::get('db.password'),
            'dbname' => Core_Config::get('db.database'),
            'charset' => 'utf8',
            'driverOptions' => array(
                1002=>'SET NAMES utf8'
            )
        );
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $em = EntityManager::create($dbParams, $config);

        $em->getConnection()
            ->getConfiguration()
            ->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

        //$meta = $em->getClassMetadata('Model_Entity_Tag')->getAssociationMappings();
        //$debug .= Core_Narzedzia::drukuj($meta, true);

        $tagRepo = $em->getRepository('Model_Entity_Tag');
        $tags = $tagRepo->findAll();
        foreach ($tags as $tag) {
            $debug .= $tag->getName() . '<br />';
        }

        $meta = $em->getClassMetadata('Model_Entity_Tag');
        $debug .= Core_Narzedzia::drukuj($meta->getIdentifierColumnNames(), true);

        $debug .= '<br />';

        //$entity = new Model_Entity_AppUser();
        //$entity->setName('qqqqqqqqqqqqqqqqqqqqqqqqqq');
        //$em->persist($entity);
        //$em->flush();

        $appUsers = $em->getRepository('Model_Entity_AppUser')->findAll();
        foreach ($appUsers as $au) {
            $debug .= $au->getName() . '<br />';
            $debug .= '- Active: ' . $au->getActive() . '<br />';
            $debug .= '- DataUtworzenia: ' . $au->getDataUtworzenia() . '<br />';
            $debug .= '- DataAktualizacji: ' . $au->getDataAktualizacji() . '<br />';
            if($au->getSupervisor()) {
                $debug .= '- Supervisor: ' . $au->getSupervisor()->getName() . '<br />';
            }
        }


        ///////////////////////////////////////////////////////////////////////////////
        /*
        $form = new Form("ZendForm");
        $form->setAttributes(array(
            'enctype' => 'multipart/form-data',
            'action' => 'ZendForm.php'
        ));

        $el = new Zend\Form\Element\Checkbox('uzytkownik');
        $el->setLabel('użytkownik');
        $form->add($el);

        $el = new Zend\Form\Element\Color('kolor');
        $el->setValue('#ff0000');
        $el->setLabel('kolor');
        $form->add($el);

        $el = new Zend\Form\Element\Text('nazwa');
        $el->setLabel('nazwa');
        $form->add($el);

        $debug .= Core_ZendForm::render($form);
        */
        ///////////////////////////////////////////////////////////////////////////////
        /*
        $acl = new Acl();
        $acl->addRole(new Role('guest'))
            ->addRole(new Role('member'))
            ->addRole(new Role('admin'));
        $parents = array('guest', 'member', 'admin');
        $acl->addRole(new Role('someUser'), $parents);
        $acl->addResource(new Resource('someResource'));
        $acl->deny('guest', 'someResource');
        $acl->allow('member', 'someResource');
        $isAllowed = $acl->isAllowed('someUser', 'someResource');
        $debug .= Core_Narzedzia::drukuj($isAllowed, true);
        $debug .= Core_Narzedzia::drukuj($acl->getResources(), true);
        $debug .= Core_Narzedzia::drukuj($acl->getRoles(), true);
        */
        ///////////////////////////////////////////////////////////////////////////////

        $o_indexResponse->dodajParametr("debug", $debug);
        $o_indexResponse->dodajParametr("modul", $this->modul);
        $o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
        return $o_indexResponse;
    }

    //================================================================================
    function testAction(Core_Request $o_requestIn)
    {
		$productMapper = new Model_Produkt();
		$productMapper->filtr_jezyk_id = 1;
		$productMapper->filtrujRekordy();
		$products = array();
		foreach($productMapper->rekordy as $productId) {
			$products[] = new Model_Produkt($productId);
		}

		$userMapper = new Model_App_UserMapper();
		$user = $userMapper->findOneById(5);

		$filteredProducts = Model_Tag_PermissionsService::filter($products, $user);

		$debug = '';
		$debug .= Core_Narzedzia::drukuj(count($products), 1);
		$debug .= Core_Narzedzia::drukuj(count($filteredProducts), 1);



		$resp = new Core_Response();
		$resp->setModuleTemplate("zend");
        $resp->dodajParametr("debug", $debug);
        $resp->dodajParametr("modul", $this->modul);
        $resp->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
        return $resp;
    }
}
