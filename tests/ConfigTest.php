<?php


use PHPUnit\Framework\TestCase;
use TestClasses\Config;

class ConfigTest extends TestCase
{
	public function testBuild()
	{
		//	Always open this configuration file with its initValue values.
		$configFile = __DIR__ . '/data/config.php';
		$config     = new Config(require $configFile);

		//	Open all other files ending with '.php' as a configuration file.
		//	'glob' defaults to sorted.
		foreach (glob(__DIR__ . '/data/*.php') as $cnf) {
			if ($cnf !== $configFile && !is_dir($cnf)) {
				$config->merge(require $cnf);
			}
		}

//		tprint($config->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/config.json',
			json_encode($config->toArray())
		);
	}
}
