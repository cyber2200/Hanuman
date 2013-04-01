<?php
namespace Hanuman\Model;

class ModulesModel
{
	/**
	 * This method will generate a new module including the following steps:
	 * 1. Create the directory tree.
	 * 2. Create the relevant files.
	 * 3. Add it to the modules array of the application.
	 * 4. Add the relevant route to the router configuration.
	 *
	 * @param string $moduleName
	 * @return array
	 */
	public function generateModule($moduleName)
	{
		// New module path
		$newModuleDir = getcwd() . '/module/' . $moduleName;
		
		// Some server side validation.
		if ($moduleName == '')
		{
			return array(
				'success' => false,
				'message' => 'Please enter a module name',
			);
		}
		
		if (! ctype_alnum($moduleName))
		{
			return array(
				'success' => false,
				'message' => 'Module name most contain only English letter or numbers',
			);			
		}
		
		if (! ctype_upper($moduleName[0]))
		{
			return array(
				'success' => false,
				'message' => 'First letter most be capital: ' . $moduleName,
			);
		}
		
		if (file_exists($newModuleDir))
		{
			return array(
				'success' => false,
				'message' => 'Module directory already exist',
			);
		}	
		
		// Let's do some work, create the directory tree
		if (! mkdir($newModuleDir))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir,
			);
		}
		
		// config tree
		if (! mkdir($newModuleDir . '/config'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/config',
			);
		}
		
		// src tree
		if (! mkdir($newModuleDir . '/src'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/src',
			);
		}
		
		if (! mkdir($newModuleDir . '/src/' . $moduleName))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/src/' . $moduleName,
			);
		}
		
		if (! mkdir($newModuleDir . '/src/' . $moduleName . '/Controller'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/src/' . $moduleName . '/Controller',
			);
		}
		
		if (! mkdir($newModuleDir . '/src/' . $moduleName . '/Model'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/src/' . $moduleName . '/Model',
			);
		}

		if (! mkdir($newModuleDir . '/src/' . $moduleName . '/Form'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/src/' . $moduleName . '/Form',
			);
		}
		
		// view tree
		if (! mkdir($newModuleDir . '/view'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/view',
			);
		}
		
		$moduleViewsDir = $moduleName;
		$moduleViewsDir[0] = strtolower($moduleViewsDir[0]);
		$caps = array();
		for ($i = 0; $i < strlen($moduleViewsDir); $i++)
		{
			if (ctype_upper($moduleViewsDir[$i]))
			{
				$caps[] = $i;
			}
		}
		$p1 = '';
		$p2 = '';
		$offset = 0;
		foreach ($caps as $cap)
		{
			$p1 = substr($moduleViewsDir, 0, $cap+$offset);
			$p2 = substr($moduleViewsDir, $cap+$offset);
			$p1[strlen($p1)] = '-';
			$p2[0] = strtolower($p2[0]);
			$offset++;
			$moduleViewsDir = $p1 . $p2;
		}
		
		if (! mkdir($newModuleDir . '/view/' . $moduleViewsDir))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $moduleViewsDir . '/view',
			);
		}
		
		if (! mkdir($newModuleDir . '/view/' . $moduleViewsDir . '/index'))
		{
			return array(
				'success' => false,
				'message' => "Can't create directory: " . $newModuleDir . '/view/' . $moduleViewsDir . '/index',
			);
		}
		
		// Let's move some files
		$templateDir = __DIR__ . '/templates/module';
		
		// LICENSE.txt
		$filename = '/LICENSE.txt';
		$bufferStr = file_get_contents($templateDir . $filename);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);				
		}
		
		// README.md
		$filename = '/README.md';
		$bufferStr = file_get_contents($templateDir . $filename);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);				
		}
		
		// autoload_classmap.php
		$filename = '/autoload_classmap.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);				
		}

		// autoload_function.php
		$filename = '/autoload_function.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);				
		}
		
		// autoload_register.php
		$filename = '/autoload_register.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);		
		}

		// Module.php
		$filename = '/Module.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);								
		}
		
		// /config/module.config.php
		$filename = '/config/module.config.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		if (file_put_contents($newModuleDir . $filename, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . $filename,
			);						
		}
		
		// /src/ZendSkeletonModule/Controller/IndexController.php
		$filename = '/src/ZendSkeletonModule/Controller/IndexController.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		if (file_put_contents($newModuleDir . "/src/{$moduleName}/Controller/IndexController.php", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . "/src/{$moduleName}/Controller/IndexController.php",
			);			
		}
		
		// /view/{$moduleViewsDir}/index/index.phtml
		$filename = "/view/zend-skeleton-module/index/index.phtml";
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		if (file_put_contents($newModuleDir . "/view/{$moduleViewsDir}/index/index.phtml", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . "/view/{$moduleViewsDir}/index/index.phtml",
			);			
		}
		
		// Adding the new module to the application config
		$appConfigPath = dirname(dirname($newModuleDir)) . '/config/application.config.php';
		$bufferStr = file_get_contents($appConfigPath);
		$startOfArray = strpos($bufferStr, "'modules' => array(");
		$endOfArray = strpos($bufferStr, ")", $startOfArray);
		$arrayLength = $endOfArray - $startOfArray + 1;
		$originalString = substr($bufferStr, $startOfArray, $arrayLength); 
		$end = strpos($originalString, ')');
		$modulesArrayString = substr($originalString, 19, $end-19);
		$modulesArray = explode(',', $modulesArrayString);
		for ($i = 0; $i < count($modulesArray); $i++)
		{
			if (trim($modulesArray[$i]) == '')
			{
				unset($modulesArray[$i]);
			}
			else
			{
				$modulesArray[$i] = trim($modulesArray[$i]);
			}
		}
		$modulesArray[] = "'" . $moduleName . "'";
		
		$newBlock = "'modules' => array(" . implode(', ', $modulesArray) . ")";
		
		$bufferStr = str_replace($originalString, $newBlock, $bufferStr);
		
		if (file_put_contents($appConfigPath, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $appConfigPath,
			);
		}
		
		return array(
			'success' => true,
			'message' => $appDir,
		);
	}
	
	/**
	 * @ToDo
	 */
	public function getModules()
	{
		$basePath = getcwd();
		$configFile = $basePath . '/config/application.config.php';
		$bufferStr = file_get_contents($configFile);
		$startOfArray = strpos($bufferStr, "'modules' => array(");
		$endOfArray = strpos($bufferStr, ")", $startOfArray);
		$arrayLength = $endOfArray - $startOfArray + 1;
		$originalString = substr($bufferStr, $startOfArray, $arrayLength); 
		$end = strpos($originalString, ')');
		$modulesArrayString = substr($originalString, 19, $end-19);
		$modulesArray = explode(',', $modulesArrayString);
		if (count($modulesArray) == 0)
		{
			return array(
				'success' => false,
				'message' => "Can't load modules",
				'data' => array()
			);			
		}
		else
		{
			for ($i = 0; $i < count($modulesArray); $i++)
			{
				$modulesArray[$i] = trim($modulesArray[$i]);
				$modulesArray[$i] = trim($modulesArray[$i], "'");
			}
			
			return array(
				'success' => true,
				'message' => print_r($modulesArray, true),
				'data' => $modulesArray
			);	
		}
	}
	
	/**
	 * @ToDo
	 */
	public function deleteModule($moduleName)
	{
		// Delete the module from the application config
		$basePath = getcwd();
		$configFile = $basePath . '/config/application.config.php';
		$bufferStr = file_get_contents($configFile);
		$startOfArray = strpos($bufferStr, "'modules' => array(");
		$endOfArray = strpos($bufferStr, ")", $startOfArray);
		$arrayLength = $endOfArray - $startOfArray + 1;
		$originalString = substr($bufferStr, $startOfArray, $arrayLength); 
		$end = strpos($originalString, ')');
		$modulesArrayString = substr($originalString, 19, $end-19);
		$modulesArray = explode(',', $modulesArrayString);
		
		for ($i = 0; $i < count($modulesArray); $i++)
		{
			$modulesArray[$i] = trim($modulesArray[$i]);
			if ($modulesArray[$i] == "'" . $moduleName . "'")
			{
				unset($modulesArray[$i]);
			}
		}
		
		$newBlock = "'modules' => array(" . implode(', ', $modulesArray) . ")";
		
		$bufferStr = str_replace($originalString, $newBlock, $bufferStr);
		
		if (file_put_contents($configFile, $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $configFile,
			);
		}
		
		$this->delFs($basePath . '/module/' . $moduleName);
		
		return array(
			'success' => true,
			'message' => '',
		);			
	}
	
	/**
	 * @ToDo
	 */
	private function delFs($moduleDir) 
	{	
		$files = array_diff(scandir($moduleDir), array('.','..')); 
		foreach ($files as $file) 
		{ 
			if (is_dir($moduleDir. '/' . $file))
			{
				$this->delFs($moduleDir . '/' . $file);
			}
			else
			{
				unlink($moduleDir . '/' . $file);
			}
		}
		return rmdir($moduleDir); 
	}
}