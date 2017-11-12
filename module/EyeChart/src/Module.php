<?php
/**
 *
 * DriverManager Module
 *
 * @package DriverManager2.0
 * @copyright 2017, Swift Transportation
 * @author Guido Faecke <guido_faecke@swifttrans.com>
 */
namespace EyeChart;

class Module
{

    /**
     * @return mixed[]
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap($e)
    {
        // Register a render event
        $app = $e->getParam('application');
        $app->getEventManager()->attach('render', array($this, 'setLayoutTitle'));
    }

    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayoutTitle($e)
    {
        $siteName = $e->getApplication()->getServiceManager()->get('config')['applicationTitle'];

        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('ViewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper = $viewHelperManager->get('headTitle');

        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');

        // Setting the action, controller, module and site name as title segments
        $headTitleHelper->append($siteName);
    }
}
