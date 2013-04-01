<?php 

/**
 * Index file. Don't change the first part.
 * 
 * @author khernik
 * 
 * ------------------------------------------------------------------------------
 */

// Set the basedir global constant
define('BASEDIR', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// Set the file structure constants
define('APATH', BASEDIR . 'application'.DIRECTORY_SEPARATOR);
define('MPATH', BASEDIR . 'modules'.DIRECTORY_SEPARATOR);
define('SPATH', BASEDIR . 'system'.DIRECTORY_SEPARATOR);

// Display installation file if exists
if(is_readable('_install.php'))
{
	require_once('_install.php');
	return;
}

// Include autoloading class
include SPATH . 'autoloader'.DIRECTORY_SEPARATOR.'autoloader.php';

// Enable autolaoding
spl_autoload_register(array('Mooo\System\Autoloader\Autoloader', 'init'));

/**
 * ------------------------------------------------------------------------------
 * 
 * This part is configurations - CAN/SHOULD be changed.
 */

// Set error reporting
error_reporting(E_ALL | E_STRICT);

// Set the timezone
date_default_timezone_set('Europe/Warsaw');

// Initialize basic values
Mooo\System\Core\Mooo::init(array(
	'base_url'          => 'Mooo/',
	'index_file'        => 'index.php/',
	'mooo_errors'       => TRUE,
	'log_errors'        => TRUE,
));

// Set default routes
Mooo\System\Route\Route::set('index', 'index');

// Enable modules
Mooo\System\Core\Mooo::modules(array(
	'database'       => MPATH.'database',        // Database access
));

/** 
 * ------------------------------------------------------------------------------
 * 
 * Supress the request
 */

echo Mooo\System\Core\Request::factory()->execute();
