<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Hanuman\Controller;

use Hanuman\Model\ModulesModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ModulesController extends AbstractActionController
{
    public function indexAction()
    {
		$this->layout('layout/hanuman');
        return array();
    }
	
	public function createAction()
	{
		$request = $this->getRequest();
		$newModuleName = $request->getPost('newModuleName', null);
		$modulesModel = new ModulesModel();
		$result = new JsonModel($modulesModel->generateModule($newModuleName));
        return $result;
	}
	
	public function getAction()
	{
		$modulesModel = new ModulesModel();
		$result = new JsonModel($modulesModel->getModules());
        return $result;
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$moduleName = $request->getPost('moduleName', null);
		$modulesModel = new ModulesModel();
		$result = new JsonModel($modulesModel->deleteModule($moduleName));
        return $result;		
	}
	
	public function editAction()
	{	
		$this->layout('layout/hanuman');
		return array();
	}
}