<?php
namespace Hanuman\Model;

use Exception;

class ApplicationModel
{
	public function getDbConfig()
	{
		$globalPath = getcwd() . '/config/autoload/global.php';
		$bufferStr = file_get_contents($globalPath);
		$dbBlockStart = strpos($bufferStr, "'db' => ");
		if ($dbBlockStart === FALSE)
		{
			$template = file_get_contents(__DIR__ . '/templates/app/global.php');
			file_put_contents($globalPath, $template);
			$this->getDbConfig();
			
		}
		else
		{
			$dbBlockEnd = 0;
			
			$c = -1;
			
			for ($i = $dbBlockStart; $i < strlen($bufferStr); $i++)
			{
				if ($bufferStr[$i] == '(')
				{
					if ($c == -1)
					{
						$c = 0;
					}
					$c++;
				}
				
				if ($bufferStr[$i] == ')')
				{
					$c--;
				}
				
				if ($c == 0)
				{
					$dbBlockEnd = $i;
					break;
				}
			}
			$dbBlock = substr($bufferStr, $dbBlockStart, $dbBlockEnd - $dbBlockStart);
			
			// dbUser
			preg_match("/'username'\s*=>\s*'(.*?)'/", $dbBlock, $matches, PREG_OFFSET_CAPTURE);
			$dbUser = $matches[1][0];
			
			// dbPass
			preg_match("/'password'\s*=>\s*'(.*?)'/", $dbBlock, $matches, PREG_OFFSET_CAPTURE);
			$dbPass = $matches[1][0];
			
			// dsn
			preg_match("/'dsn'\s*=>\s*'(.*?)'/", $dbBlock, $matches);
			$dsn = $matches[1];
			preg_match("/mysql:dbname=(.*?);/", $dsn, $matches);
			$dbName = $matches[1];
			preg_match("/host=(.*?)$/", $dsn, $matches);
			$dbHost = $matches[1];

			return array(
				'dbHost' => $dbHost,
				'dbName' => $dbName,
				'dbUser' => $dbUser,
				'dbPass' => $dbPass,
			);
		}
	}
	
	public function saveConfig($postData)
	{
		$appConfigPath = getcwd() . '/config/autoload/global.php';
		$bufferStr = file_get_contents($appConfigPath);
		preg_match("/'db'[\s|\n|\t]*=>[\s|\n|\t]*array\((.*?)\)/s", $bufferStr, $matches);
		$origBlock = $matches[0];
		$dbBlock = $matches[1];
		preg_match("/'dsn'[\s\t\n]*=>[\s\t\n]*'(.*?)'/s", $dbBlock, $matches);
		$origDsn = $matches[1];
		$dsn = $matches[1];
		preg_match("/mysql:dbname=(.*?);/s", $dsn, $matches);
		$dsn = preg_replace("/^mysql:dbname=(.*?);/s", "mysql:dbname={$postData['dbName']};", $dsn);
		$dsn = preg_replace("/;host=(.*?)$/s", ";host={$postData['dbHost']}", $dsn);
		
		$bufferStr = str_replace($origDsn, $dsn, $bufferStr);
		$bufferStr = preg_replace("/'username'[\s\t\n]*=>[\s\t\n]*'(.*?)'/s", "'username' => '{$postData['dbUser']}'", $bufferStr);
		$bufferStr = preg_replace("/'password'[\s\t\n]*=>[\s\t\n]*'(.*?)'/s", "'password' => '{$postData['dbPass']}'", $bufferStr);
		
		if (! file_put_contents($appConfigPath, $bufferStr))
		{
			return array(
				'success' => false,
				'message' => "Can't write file: " . $appConfigPath
			);
		}
		
		return array(
			'success' => true,
			'message' => ""
		);
	}
	
	public function testDbCon($dbAdapter)
	{
		try
		{
			$dbAdapter->query("SHOW TABLES;");
			return array(
				'success' => true,
				'message' => ''
			);	
		}
		catch(Exception $ex)
		{
			return array(
				'success' => false,
				'message' => $ex->getMessage()
			);	
		}

	}
}