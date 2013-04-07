<?php
namespace Hanuman\Controller;

use Hanuman\Model\ApplicationModel;
use Zend\Mvc\Controller\AbstractActionController;

class ConfigController extends AbstractActionController
{
    public function indexAction()
    {
		$this->layout('layout/hanuman');	
		$applicationModel = new ApplicationModel();
		$dbConfig = $applicationModel->getDbConfig();
        return array('dbConfig' => $dbConfig);
    }
}
