<?php
/*
 Minimal mysql_* compatibility layer for Joomla 1.0 on PHP ≥7.0 using mysqli.
 Only functions used by installer and basic runtime are provided.
*/

// Soften legacy noise from PHP 7.x while keeping fatals visible during install
@ini_set('display_errors', 'On');
@ini_set('error_reporting', (string) (E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE & ~E_USER_NOTICE & ~E_WARNING));

// Suppress legacy deprecations/notices noisily emitted by installer
set_error_handler(function($severity, $message, $file, $line) {
    if ($severity === E_DEPRECATED || $severity === E_USER_DEPRECATED || $severity === E_NOTICE || $severity === E_USER_NOTICE) {
        return true; // handled = suppress
    }
    return false; // allow normal handling for warnings/fatals
});

// Magic quotes shims (removed in PHP 7). Joomla 1.0 expects these to exist.
if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) { /* no-op on modern PHP */ return false; }
}
if (!function_exists('get_magic_quotes_runtime')) {
    function get_magic_quotes_runtime() { return 0; }
}

if (!function_exists('mysql_connect')) {
    $GLOBALS['__mysql_compat_links'] = [];

    function mysql_connect($host = null, $user = null, $password = null, $new_link = false, $client_flags = 0) {
        $link = @mysqli_connect($host, $user, $password);
        if ($link) {
            $GLOBALS['__mysql_compat_links'][(int)$link] = $link;
        }
        return $link;
    }

    function mysql_select_db($database_name, $link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_select_db($link, $database_name) : false;
    }

    function mysql_query($query, $link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_query($link, $query) : false;
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    function mysql_fetch_row($result) {
        return mysqli_fetch_row($result);
    }

    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
    }

    function mysql_error($link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_error($link) : '';
    }

    function mysql_close($link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_close($link) : false;
    }

    function mysql_errno($link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_errno($link) : mysqli_connect_errno();
    }

    function mysql_insert_id($link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_insert_id($link) : 0;
    }

    function mysql_real_escape_string($unescaped_string, $link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_real_escape_string($link, $unescaped_string) : addslashes($unescaped_string);
    }

    function mysql_affected_rows($link_identifier = null) {
        $link = $link_identifier ?: (count($GLOBALS['__mysql_compat_links']) ? end($GLOBALS['__mysql_compat_links']) : null);
        return $link ? mysqli_affected_rows($link) : 0;
    }

    function mysql_free_result($result) {
        return $result ? mysqli_free_result($result) : false;
    }
}

// ereg/eregi polyfills
if (!function_exists('ereg')) {
    function ereg($pattern, $string, &$regs = null) {
        $re = '/' . str_replace('/', '\/', $pattern) . '/';
        $result = preg_match($re, $string, $m);
        if ($result && $regs !== null) { $regs = $m; }
        return $result;
    }
}
if (!function_exists('eregi')) {
    function eregi($pattern, $string, &$regs = null) {
        $re = '/' . str_replace('/', '\/', $pattern) . '/i';
        $result = preg_match($re, $string, $m);
        if ($result && $regs !== null) { $regs = $m; }
        return $result;
    }
}

// preg_explode compatibility (older Joomla used this alias)
if (!function_exists('preg_explode')) {
    function preg_explode($pattern, $subject, $limit = -1, $flags = 0) {
        return preg_split($pattern, $subject, $limit, $flags);
    }
}
