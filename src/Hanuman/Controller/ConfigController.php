<?php
namespace Hanuman\Controller;

use Hanuman\Model\ApplicationModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ConfigController extends AbstractActionController
{
    public function indexAction()
    {
		$this->layout('layout/hanuman');	
		$applicationModel = new ApplicationModel();
		$dbConfig = $applicationModel->getDbConfig();
		if (! $dbConfig)
		{
			$dbConfig = $applicationModel->getDbConfig();
		}
        return array(
			'dbConfig' => $dbConfig,
			'baseUrl' => $this->request->getBasePath(),
		);
    }
	
	public function saveAction()
	{
		$postData = $request = $this->getRequest()->getPost();
		$applicationModel = new ApplicationModel();
		return(new JsonModel($applicationModel->saveConfig($postData)));
	}
	
	public function testdbconAction()
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$applicationModel = new ApplicationModel();
		return(new JsonModel($applicationModel->testDbCon($dbAdapter)));
	}
}
