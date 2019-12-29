<?php
//PHP_SAPI == 'cli'
// Clean argument values
$phpStormRunner = null;
$cleanedArgv = array();
foreach ($_SERVER['argv'] as $key => $value) {
    if (strpos($value, 'ide-phpunit.php') === false) {
        $cleanedArgv[] = $value;
    } else {
        $phpStormRunner = $value;
    }
}
$_SERVER['argv'] = $cleanedArgv;

// Cleanup PhpStorm runner
if (is_null($phpStormRunner)) {
    die('Not running in PhpStorm');
}
$phpStormRunnerContents = file_get_contents($phpStormRunner);
$phpStormRunnerContents = str_replace('IDE_PHPUnit_TextUI_Command::main', 'IDE_Cake_PHPUnit_TextUI_Command::main', $phpStormRunnerContents);
$phpStormRunnerContents = str_replace('IDE_Cake_PHPUnit_TextUI_Command::main()', '//IDE_Cake_PHPUnit_TextUI_Command::main()', $phpStormRunnerContents);
file_put_contents($phpStormRunner, $phpStormRunnerContents);

// Include PhpStorm runner
include($phpStormRunner);

// Let's bake some CakePHP
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
    define('ROOT', dirname(__FILE__));
}
if (!defined('APP_DIR')) {
    define('APP_DIR', 'app');
}
if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', APP_DIR . DS . 'webroot');
}
if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', WEBROOT_DIR . DS);
}
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    if (function_exists('ini_set')) {
        ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
    }
    if (!include ('Cake' . DS . 'bootstrap.php')) {
        $failed = true;
    }
} else {
    if (!include (CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
        $failed = true;
    }
}
if (!empty($failed)) {
    trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}
if (Configure::read('debug') < 1) {
    die(__d('cake_dev', 'Debug setting does not allow access to this url.'));
}

// Do some reconfiguration
Configure::write('Error', array());
Configure::write('Exception', array());

// Bootstrap CakePHP
require_once CAKE . 'TestSuite' . DS . 'CakeTestSuiteDispatcher.php';
require_once CAKE . 'TestSuite' . DS . 'CakeTestSuiteCommand.php';

// Define new runner
class IDE_Cake_PHPUnit_TextUI_Command extends CakeTestSuiteCommand
{
    public static function main($exit = TRUE)
    {
        // Parse CakePHP-specific test runner options
        // And remove CakePHP-specific test runner options from ARGV
        $cakePhpOptions = array('case' => 'AllTests');
        $cleanedArgv = array();
        foreach ($_SERVER['argv'] as $key => $value) {
            if (strpos($value, '--cake-') !== false) {
                $option = explode('=', $value);
                $cakePhpOptions[str_replace('--cake-', '', $option[0])] = isset($option[1]) ? $option[1] : 'true';
            } else {
                $cleanedArgv[] = $value;
            }
        }

        // Run!
        $command = new IDE_Cake_PHPUnit_TextUI_Command('CakeTestLoader', $cakePhpOptions);
        $command->run($cleanedArgv, $exit);
    }

    protected function handleArguments(array $argv)
    {
        parent::handleArguments($argv);
        if (isset($this->arguments['printer'])) {
            $printer = $this->arguments['printer'];
        } else {
            $printer = null;
        }
        $printer = new IDE_PHPUnit_TextUI_ResultPrinter($printer);
        $this->arguments['printer'] = $printer;
        $this->arguments['listeners'][] = new IDE_PHPUnit_Framework_TestListener($printer);
    }
}
IDE_Cake_PHPUnit_TextUI_Command::main();