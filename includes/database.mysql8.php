<?php
/**
 * @version    database.mysql8.php shim
 * @package    Joomla
 * @subpackage Database
 *
 * Minimal MySQL 8 driver that reuses the MySQL 5 (mysqli) driver.
 * This allows selecting `database.mysql8.php` in configuration with no other changes.
 */

// no direct access
defined('_VALID_MOS') or die('Restricted access');

// Reuse the mysqli-based MySQL5 driver implementation
require_once dirname(__FILE__) . '/database.mysql5.php';

// Optional: set a flag other code can read if needed
if (!defined('JOOMLA_DB_MYSQL8')) {
	define('JOOMLA_DB_MYSQL8', true);
}

/**
 * Provide a tiny override hook consumed by core database.php without modifying
 * its logic. This keeps core close to upstream while enabling MySQL 8 behavior.
 */
if (!function_exists('joomla_mysql8_set_sql_mode')) {
	function joomla_mysql8_set_sql_mode($mysqliResource, $mode = '')
	{
		@mysqli_query($mysqliResource, "SET SESSION sql_mode=''");
		return true;
	}
}
