<?php
/**
 * Test bootstrap file for Joomla 1.0 testing suite
 * 
 * This file sets up the testing environment and includes necessary
 * Joomla files for testing.
 */

// Define test constants
define('_VALID_MOS', 1);
define('_MOS_MAMBO_INCLUDED', 1);
define('RG_EMULATION', 0);

// Set error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up test environment variables
if (!defined('JOOMLA_ROOT')) {
    define('JOOMLA_ROOT', getenv('JOOMLA_ROOT') ?: dirname(__DIR__));
}

// Include Joomla core files
require_once JOOMLA_ROOT . '/globals.php';
require_once JOOMLA_ROOT . '/configuration.php';
require_once JOOMLA_ROOT . '/includes/joomla.php';

// Set up test database connection
if (getenv('DB_HOST')) {
    $GLOBALS['mosConfig_host'] = getenv('DB_HOST');
    $GLOBALS['mosConfig_user'] = getenv('DB_USER');
    $GLOBALS['mosConfig_password'] = getenv('DB_PASS');
    $GLOBALS['mosConfig_db'] = getenv('DB_NAME');
    $GLOBALS['mosConfig_dbprefix'] = getenv('DB_PREFIX') ?: 'jos_';
}

// Initialize database for testing
if (isset($GLOBALS['mosConfig_host'])) {
    $database = new database($GLOBALS['mosConfig_host'], $GLOBALS['mosConfig_user'], $GLOBALS['mosConfig_password'], $GLOBALS['mosConfig_db'], $GLOBALS['mosConfig_dbprefix']);
}

// Include test helpers
require_once __DIR__ . '/helpers/TestHelper.php';
require_once __DIR__ . '/helpers/DatabaseHelper.php';
require_once __DIR__ . '/helpers/MockHelper.php';

// Set up test data directory
if (!defined('TEST_DATA_DIR')) {
    define('TEST_DATA_DIR', __DIR__ . '/fixtures');
}

// Create test data directory if it doesn't exist
if (!is_dir(TEST_DATA_DIR)) {
    mkdir(TEST_DATA_DIR, 0755, true);
}

// Set up temporary directory for tests
if (!defined('TEST_TEMP_DIR')) {
    define('TEST_TEMP_DIR', sys_get_temp_dir() . '/joomla-tests-' . uniqid());
    mkdir(TEST_TEMP_DIR, 0755, true);
}

// Register cleanup function
register_shutdown_function(function() {
    if (defined('TEST_TEMP_DIR') && is_dir(TEST_TEMP_DIR)) {
        // Clean up temporary test files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(TEST_TEMP_DIR, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        
        rmdir(TEST_TEMP_DIR);
    }
});

// Mock global variables for testing
$GLOBALS['_MOS_OPTION'] = array();
$GLOBALS['_MAMBOTS'] = new stdClass();
$GLOBALS['_MAMBOTS']->loadBotGroup = function($group) { return true; };
$GLOBALS['_MAMBOTS']->trigger = function($event) { return true; };

// Set up test user session
$GLOBALS['my'] = new stdClass();
$GLOBALS['my']->id = 1;
$GLOBALS['my']->username = 'admin';
$GLOBALS['my']->name = 'Administrator';
$GLOBALS['my']->email = 'admin@test.com';
$GLOBALS['my']->usertype = 'Super Administrator';
$GLOBALS['my']->gid = 25;
$GLOBALS['my']->block = 0;

// Set up mainframe for testing
$GLOBALS['mainframe'] = new mosMainFrame($database, 'com_content', '.');
$GLOBALS['mainframe']->initSession();

echo "Joomla 1.0 Test Environment Initialized\n";
