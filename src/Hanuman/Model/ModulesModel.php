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
		
		// /src/TempModule/Controller/IndexController.php
		$filename = '/src/TempModule/Controller/IndexController.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		$bufferStr = str_replace('##CONTROLLER_NAME##', 'Index', $bufferStr);
		
		if (file_put_contents($newModuleDir . "/src/{$moduleName}/Controller/IndexController.php", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . "/src/{$moduleName}/Controller/IndexController.php",
			);			
		}
		
		// /src/TempModule/Model/UtilsModel.php
		$filename = '/src/TempModule/Model/UtilsModel.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		$bufferStr = str_replace('##MODEL_NAME##', 'UtilsModel', $bufferStr);
		
		if (file_put_contents($newModuleDir . "/src/{$moduleName}/Model/UtilsModel.php", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . "/src/{$moduleName}/Model/UtilsModel.php",
			);			
		}
		
		// /view/{$moduleViewsDir}/index/index.phtml
		$filename = "/view/temp-module/index/index.phtml";
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		$bufferStr = str_replace('##CONTROLLER_NAME##', 'Index', $bufferStr);
		$bufferStr = str_replace('##ACTION_NAME##', 'index', $bufferStr);
		
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
		preg_match("/'modules'[\s|\n|\t]*=>[\s|\n|\t]*array\((.*?)\)/s", $bufferStr, $matches);
		$origString = $matches[0];
		$modulesArray = explode(',', $matches[1]);
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
		$newString = "'modules' => array(". implode(", ", $modulesArray) .")";
		$bufferStr = str_replace($origString, $newString, $bufferStr, $c);
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
		$appConfigPath = getcwd() . '/config/application.config.php';
		$bufferStr = file_get_contents($appConfigPath);
		preg_match("/'modules'[\s|\n|\t]*=>[\s|\n|\t]*array\((.*?)\)/s", $bufferStr, $matches);
		$origString = $matches[0];
		$modulesArray = explode(',', $matches[1]);
		
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
				if ($modulesArray[$i] == '')
				{
					unset($modulesArray[$i]);
				}
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
		$appConfigPath = getcwd() . '/config/application.config.php';
		$bufferStr = file_get_contents($appConfigPath);
		preg_match("/'modules'[\s|\n|\t]*=>[\s|\n|\t]*array\((.*?)\)/s", $bufferStr, $matches);
		$origString = $matches[0];
		$modulesArray = explode(',', $matches[1]);
		
		for ($i = 0; $i < count($modulesArray); $i++)
		{
			$modulesArray[$i] = trim($modulesArray[$i]);
			if ($modulesArray[$i] == "'" . $moduleName . "'")
			{
				unset($modulesArray[$i]);
			}
		}
		
		$newBlock = "'modules' => array(" . implode(', ', $modulesArray) . ")";
		
		$bufferStr = str_replace($origString, $newBlock, $bufferStr);
		
		if (file_put_contents($appConfigPath, $bufferStr) === FALSE)
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
	
	public function getControllers($moduleName)
	{
		$basePath = getcwd();
		$controllersPath = $basePath . '/module/' . $moduleName . '/src/' . $moduleName . '/Controller';
		$controllersArr = array();
		if($handle = opendir($controllersPath))
		{
			while (false !== ($entry = readdir($handle))) 
			{
				if ($entry != "." && $entry != "..") 
				{
					$controllersArr[] = str_replace('.php', '', $entry);
				}
			}
			closedir($handle);
		}
		else
		{
			return array(
				'success' => false,
				'message' => "Can't read the direcotry: " . $controllersPath,
				'data' => ''
			);			
		}
		return array(
			'success' => true,
			'message' => '',
			'data' => $controllersArr
		);	
	}
	
	public function getModels($moduleName)
	{
		$basePath = getcwd();
		$modelsPath = $basePath . '/module/' . $moduleName . '/src/' . $moduleName . '/Model';
		$modelsArr = array();
		if($handle = opendir($modelsPath))
		{
			while (false !== ($entry = readdir($handle))) 
			{
				if ($entry != "." && $entry != "..") 
				{
					if (! is_dir($modelsPath . '/' . $entry))
					{
						$modelsArr[] = str_replace('.php', '', $entry);
					}
				}
			}
			closedir($handle);
		}
		else
		{
			return array(
				'success' => false,
				'message' => "Can't read the direcotry: " . $modelsPath,
				'data' => ''
			);	
		}
		return array(
			'success' => true,
			'message' => '',
			'data' => $modelsArr
		);	
	}
	
	public function createController($moduleName, $newControllerName)
	{
		// /src/TempModule/Controller/IndexController.php
		$templateDir = __DIR__ . '/templates/module';
		$filename = '/src/TempModule/Controller/IndexController.php';
		$moduleDir = getcwd() . '/module/' . $moduleName;
		
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		$bufferStr = str_replace('##CONTROLLER_NAME##', $newControllerName, $bufferStr);
		
		if (file_put_contents($moduleDir . "/src/{$moduleName}/Controller/". $newControllerName ."Controller.php", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $moduleDir . "/src/{$moduleName}/Controller/". $newControllerName ."Controller.php",
			);			
		}
		
		$bufferStr = file_get_contents($moduleDir . '/config/module.config.php');
		$startStr = "'controllers'";
		$start = strpos($bufferStr, $startStr);
		$block = substr($bufferStr, $start + strlen($startStr));
		
		$innerArrayEnd = 0;
		$c = -1;
		$r = 0;
		for ($i = 0; $i < strlen($block); $i++)
		{
			if ($block[$i] == '(')
			{
				if ($c == -1)
				{
					$c = 0;
				}
				$c++;
			}
			
			if ($block[$i] == ')')
			{
				$c--;
				if ($c == 1)
				{
					$innerArrayEnd = $i;
				}
			}
			
			if ($c == 0)
			{
				$r++;
				$c = -1;
			}
			
			if ($r == 1)
			{
				break;
			}
		}
		$block = substr($block, 0, $innerArrayEnd);
		$blockRep = substr($block, 0, $innerArrayEnd);
		$blockRep = $blockRep .  "\t'". $moduleName ."\Controller\\". $newControllerName ."' => '". $moduleName ."\Controller\\". $newControllerName ."Controller',\n\t\t";
		$bufferStr = str_replace($block, $blockRep, $bufferStr);
		if (file_put_contents($moduleDir . '/config/module.config.php', $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $templateDir . $filename
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
		
		if (! mkdir($moduleDir . '/view/' . $moduleViewsDir . '/' . strtolower($newControllerName)))
		{
			return array(
				'success' => false,
				'message' => "Can't create direcotry: " . $moduleDir . '/view/' . $moduleViewsDir . '/' . strtolower($newControllerName)
			);
		}
		
		// /view/{$moduleViewsDir}/index/index.phtml
		$filename = "/view/temp-module/index/index.phtml";
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		$bufferStr = str_replace('##CONTROLLER_NAME##', $newControllerName, $bufferStr);
		$bufferStr = str_replace('##ACTION_NAME##', 'index', $bufferStr);
		
		if (file_put_contents($moduleDir . "/view/{$moduleViewsDir}/". strtolower($newControllerName) ."/index.phtml", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . "/view/{$moduleViewsDir}/index/index.phtml",
			);			
		}
		
		return array(
			'success' => true,
			'message' => '',
			'data' => ''
		);			
	}
	
	public function createModel($moduleName, $newModelName)
	{
		$moduleDir = getcwd() . '/module/' . $moduleName;
		$templateDir = __DIR__ . '/templates/module';
		
		// /src/TempModule/Model/UtilsModel.php
		$filename = '/src/TempModule/Model/UtilsModel.php';
		$bufferStr = file_get_contents($templateDir . $filename);
		$bufferStr = str_replace('##MOUDLE_NAME##', $moduleName, $bufferStr);
		$bufferStr = str_replace('##MODEL_NAME##', $newModelName, $bufferStr);
		
		if (file_put_contents($moduleDir . "/src/{$moduleName}/Model/". $newModelName ."Model.php", $bufferStr) === FALSE)
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $newModuleDir . "/src/{$moduleName}/Model/UtilsModel.php",
			);			
		}
		
		return array(
			'success' => true,
			'message' => '',
			'data' => ''
		);	
	}
	
	public function delModel($moduleName, $modelName)
	{
		$moduleDir = getcwd() . '/module/' . $moduleName;
		if (! unlink($moduleDir . "/src/{$moduleName}/Model/". $modelName .".php"))
		{
			return array(
				'success' => false,
				'message' => "Can't delete file: " . $moduleDir . "/src/{$moduleName}/Model/". $modelName ."Model.php",
				'data' => ''
			);			
		}
		
		return array(
			'success' => true,
			'message' => '',
			'data' => ''
		);	
	}
}