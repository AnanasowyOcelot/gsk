<?php

use Zend\Form\Form;
use Zend\Form\View\Helper;
use Zend\Form\View\HelperConfig;
use Zend\View\Renderer\PhpRenderer;

class Core_ZendForm
{
    public static function render(Form $form)
    {
        $zfView = new PhpRenderer();
        $plugins = $zfView->getHelperPluginManager();
        $config  = new HelperConfig;
        $config->configureServiceManager($plugins);
        $formhelper   = new Helper\Form();
        $formhelper->setView($zfView);
        return $formhelper->render($form);
    }
}
