<?php
/**
 * Complete Joomla 1.0 Compatibility Patch
 * 
 * This file applies comprehensive fixes for PHP 7.4+ compatibility issues
 * across all PHP files in Joomla 1.0
 */

function applyCompleteJoomlaCompatibilityPatches() {
    $rootDir = __DIR__ . '/..';
    $phpFiles = findPhpFiles($rootDir);
    
    $totalFiles = count($phpFiles);
    $processedFiles = 0;
    
    echo "Found $totalFiles PHP files to process...\n";
    
    foreach ($phpFiles as $file) {
        if (applyCompatibilityFixes($file)) {
            $processedFiles++;
        }
    }
    
    echo "Successfully processed $processedFiles out of $totalFiles files.\n";
    return $processedFiles === $totalFiles;
}

function findPhpFiles($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            // Skip vendor and test directories
            $path = $file->getPathname();
            if (strpos($path, '/vendor/') === false && 
                strpos($path, '/tests/') === false &&
                strpos($path, '/REFACTO/') === false) {
                $files[] = $path;
            }
        }
    }
    
    return $files;
}

function applyCompatibilityFixes($filePath) {
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Fix func_get_args() reference issue
    $content = str_replace(
        '$args =& func_get_args();',
        '$args = func_get_args();',
        $content
    );
    
    // Fix set_magic_quotes_runtime() which was removed in PHP 7.4
    $content = str_replace(
        '@set_magic_quotes_runtime(0);',
        '// @set_magic_quotes_runtime(0); // Removed in PHP 7.4',
        $content
    );
    
    $content = str_replace(
        '@set_magic_quotes_runtime(1);',
        '// @set_magic_quotes_runtime(1); // Removed in PHP 7.4',
        $content
    );
    
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
    
    // Fix split() function calls
    $content = preg_replace('/split\s*\(\s*([^,]+),\s*([^)]+)\)/', 'explode($1, $2)', $content);
    
    // Fix ereg() function calls
    $content = preg_replace('/ereg\s*\(\s*([^,]+),\s*([^)]+)\)/', 'preg_match($1, $2)', $content);
    
    // Fix eregi() function calls
    $content = preg_replace('/eregi\s*\(\s*([^,]+),\s*([^)]+)\)/', 'preg_match($1, $2, $matches, PREG_OFFSET_CAPTURE)', $content);
    
    // Fix mysql_* functions (basic replacements)
    $content = str_replace('mysql_connect(', 'mysqli_connect(', $content);
    $content = str_replace('mysql_query(', 'mysqli_query($connection, ', $content);
    $content = str_replace('mysql_fetch_array(', 'mysqli_fetch_array(', $content);
    $content = str_replace('mysql_num_rows(', 'mysqli_num_rows(', $content);
    $content = str_replace('mysql_error(', 'mysqli_error($connection)', $content);
    
    // Only write if content changed
    if ($content !== $originalContent) {
        if (file_put_contents($filePath, $content)) {
            echo "Fixed: " . basename($filePath) . "\n";
            return true;
        } else {
            echo "Failed to write: " . basename($filePath) . "\n";
            return false;
        }
    }
    
    return true;
}

// Apply patches if this file is run directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    applyCompleteJoomlaCompatibilityPatches();
}
?>
