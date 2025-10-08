#!/bin/bash

# Test script for MySQL 8.4 Legacy Mode Configuration
# This script verifies that MySQL 8.4 is running with proper legacy mode settings

echo "=== MySQL 8.4 Legacy Mode Test ==="
echo "Testing Joomla 1.0 compatibility settings..."
echo

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
sleep 10

# Test MySQL connection and configuration
echo "Testing MySQL connection..."
docker-compose exec -T joomla-db mysql -u root -prootpassword -e "
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
"

echo
echo "Testing database and user creation..."
docker-compose exec -T joomla-db mysql -u root -prootpassword -e "
SHOW DATABASES LIKE 'joomla_test';
SELECT user, host, plugin FROM mysql.user WHERE user = 'joomla';
"

echo
echo "Testing Joomla 1.0 table creation compatibility..."
docker-compose exec -T joomla-db mysql -u joomla -pjoomlapassword joomla_test -e "
SHOW TABLES;
DESCRIBE jos_users;
"

echo
echo "Testing legacy SQL compatibility..."
docker-compose exec -T joomla-db mysql -u joomla -pjoomlapassword joomla_test -e "
-- Test legacy SQL syntax that Joomla 1.0 might use
SELECT COUNT(*) as user_count FROM jos_users;
SELECT * FROM jos_users WHERE username = 'admin';
"

echo
echo "=== Test Complete ==="
echo "If all tests passed, MySQL 8.4 is properly configured for Joomla 1.0 legacy mode."
