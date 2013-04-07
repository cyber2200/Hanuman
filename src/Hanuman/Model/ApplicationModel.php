<?php
namespace Hanuman\Model;

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
}