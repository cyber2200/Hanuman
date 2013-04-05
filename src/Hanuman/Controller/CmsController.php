<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Hanuman\Controller;

use Hanuman\Model\UtilsModel;
use Zend\Mvc\Controller\AbstractActionController;

class CmsController extends AbstractActionController
{
    public function indexAction()
    {
		$this->layout('layout/hanuman');
        return array();
    }
}
