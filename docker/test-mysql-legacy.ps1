# Test script for MySQL 8.4 Legacy Mode Configuration
# This script verifies that MySQL 8.4 is running with proper legacy mode settings

Write-Host "=== MySQL 8.4 Legacy Mode Test ===" -ForegroundColor Green
Write-Host "Testing Joomla 1.0 compatibility settings..." -ForegroundColor Yellow
Write-Host ""

# Wait for MySQL to be ready
Write-Host "Waiting for MySQL to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Test MySQL connection and configuration
Write-Host "Testing MySQL connection..." -ForegroundColor Yellow
docker-compose exec -T joomla-db mysql -u root -prootpassword -e @"
SELECT 'MySQL Version:' as Info, VERSION() as Value
UNION ALL
SELECT 'SQL Mode:', @@sql_mode
UNION ALL  
SELECT 'Auth Plugin:', @@default_authentication_plugin
UNION ALL
SELECT 'Character Set:', @@character_set_server
UNION ALL
SELECT 'Collation:', @@collation_server
UNION ALL
SELECT 'Lower Case Names:', @@lower_case_table_names
UNION ALL
SELECT 'InnoDB Strict Mode:', @@innodb_strict_mode
UNION ALL
SELECT 'Function Creators:', @@log_bin_trust_function_creators;
"@

Write-Host ""
Write-Host "Testing database and user creation..." -ForegroundColor Yellow
docker-compose exec -T joomla-db mysql -u root -prootpassword -e @"
SHOW DATABASES LIKE 'joomla_test';
SELECT user, host, plugin FROM mysql.user WHERE user = 'joomla';
"@

Write-Host ""
Write-Host "Testing Joomla 1.0 table creation compatibility..." -ForegroundColor Yellow
docker-compose exec -T joomla-db mysql -u joomla -pjoomlapassword joomla_test -e @"
SHOW TABLES;
DESCRIBE jos_users;
"@

Write-Host ""
Write-Host "Testing legacy SQL compatibility..." -ForegroundColor Yellow
docker-compose exec -T joomla-db mysql -u joomla -pjoomlapassword joomla_test -e @"
-- Test legacy SQL syntax that Joomla 1.0 might use
SELECT COUNT(*) as user_count FROM jos_users;
SELECT * FROM jos_users WHERE username = 'admin';
"@

Write-Host ""
Write-Host "=== Test Complete ===" -ForegroundColor Green
Write-Host "If all tests passed, MySQL 8.4 is properly configured for Joomla 1.0 legacy mode." -ForegroundColor Cyan
