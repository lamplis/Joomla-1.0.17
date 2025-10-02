<?php
/**
 * Joomla 1.0 Compatibility Patch
 * 
 * This file contains fixes for PHP 7.4+ compatibility issues in Joomla 1.0
 * Apply these fixes before running tests to ensure compatibility
 */

// Fix for func_get_args() reference issue in joomla.php line 5167
function applyJoomlaCompatibilityPatches() {
    $joomlaFile = __DIR__ . '/../includes/joomla.php';
    
    if (!file_exists($joomlaFile)) {
        echo "Joomla.php not found at: $joomlaFile\n";
        return false;
    }
    
    $content = file_get_contents($joomlaFile);
    
    // Fix func_get_args() reference issue
    $content = str_replace(
        '$args =& func_get_args();',
        '$args = func_get_args();',
        $content
    );
    
    // Fix other potential PHP 7.4+ compatibility issues
    $content = str_replace(
        'ereg(',
        'preg_match(',
        $content
    );
    
    $content = str_replace(
        'split(',
        'explode(',
        $content
    );
    
    // Fix set_magic_quotes_runtime() which was removed in PHP 7.4
    $content = str_replace(
        'set_magic_quotes_runtime(0);',
        '// set_magic_quotes_runtime(0); // Removed in PHP 7.4',
        $content
    );
    
    $content = str_replace(
        'set_magic_quotes_runtime(1);',
        '// set_magic_quotes_runtime(1); // Removed in PHP 7.4',
        $content
    );
    
    // Write the patched content back
    if (file_put_contents($joomlaFile, $content)) {
        echo "Joomla compatibility patches applied successfully!\n";
        return true;
    } else {
        echo "Failed to apply compatibility patches!\n";
        return false;
    }
}

// Apply patches if this file is run directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    applyJoomlaCompatibilityPatches();
}
?>
