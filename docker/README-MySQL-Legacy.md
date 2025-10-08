# MySQL 8.4 Legacy Mode Configuration for Joomla 1.0

This document explains the MySQL 8.4 configuration with legacy mode settings to ensure maximum compatibility with Joomla 1.0.

## Overview

The Docker configuration has been updated to use MySQL 8.4 (latest stable) with comprehensive legacy mode settings that maintain compatibility with Joomla 1.0 while providing modern MySQL features and security.

## Key Configuration Changes

### 1. MySQL Version Upgrade
- **From**: MySQL 8.0
- **To**: MySQL 8.4 (latest stable)
- **Rationale**: Latest features, security patches, and performance improvements

### 2. Legacy Mode Settings

#### SQL Mode Configuration
```sql
sql_mode = ""
```
- Completely relaxed SQL mode for maximum legacy compatibility
- Allows deprecated SQL syntax and behaviors expected by Joomla 1.0

#### Authentication Plugin
```sql
default_authentication_plugin = mysql_native_password
```
- Uses legacy native password authentication
- Compatible with older PHP MySQL extensions and Joomla 1.0 authentication

#### Character Set and Collation
```sql
character-set-server = utf8mb4
collation-server = utf8mb4_general_ci
```
- Modern UTF-8 support while maintaining compatibility
- Full Unicode support for international content

#### Table Name Case Sensitivity
```sql
lower_case_table_names = 0
```
- Maintains Linux default behavior
- Preserves case sensitivity for table names as expected by Joomla 1.0

### 3. Performance and Compatibility Settings

#### InnoDB Configuration
```sql
innodb_strict_mode = 0
innodb_default_row_format = dynamic
```
- Relaxed InnoDB mode for legacy compatibility
- Dynamic row format for better performance with modern MySQL

#### Function Creation Permissions
```sql
log_bin_trust_function_creators = 1
```
- Allows creation of stored functions and procedures
- Required for some Joomla 1.0 extensions

#### Network and Connection Settings
```sql
max_allowed_packet = 64M
wait_timeout = 28800
interactive_timeout = 28800
```
- Increased packet size for large content
- Extended timeouts for long-running operations

### 4. Database Schema Updates

#### Table Definitions
- Updated all table definitions to use MySQL 8.4 compatible syntax
- Changed `auto_increment` to `AUTO_INCREMENT`
- Changed `default 'value'` to `DEFAULT value`
- Updated datetime defaults from `'0000-00-00 00:00:00'` to `'1000-01-01 00:00:00'`
- Updated character set from `utf8` to `utf8mb4`

#### Engine and Collation
- Maintained MyISAM engine for compatibility
- Updated to `utf8mb4_general_ci` collation

## Files Modified

### 1. `docker-compose.yml`
- Updated MySQL image to `mysql:8.4`
- Added comprehensive command-line legacy mode flags
- Added MySQL legacy configuration file mount

### 2. `docker/configs/mysql-legacy.cnf`
- New comprehensive MySQL configuration file
- Contains all legacy mode settings
- Organized by configuration sections

### 3. `docker/configs/mysql-init.sql`
- Updated initialization script for MySQL 8.4
- Modernized SQL syntax while maintaining compatibility
- Enhanced user creation and privilege management

## Usage

### Starting the Environment
```bash
cd docker
docker-compose up -d
```

### Verifying MySQL Configuration
```bash
# Connect to MySQL container
docker-compose exec joomla-db mysql -u root -p

# Check SQL mode
SELECT @@sql_mode;

# Check authentication plugin
SELECT @@default_authentication_plugin;

# Check character set
SELECT @@character_set_server, @@collation_server;
```

### Testing Joomla 1.0 Compatibility
1. Access Joomla at `http://localhost:8082`
2. Run the Joomla installer
3. Verify database connection works
4. Test core Joomla 1.0 functionality

## Benefits of This Configuration

### 1. Modern MySQL Features
- Latest security patches and bug fixes
- Improved performance and scalability
- Better memory management
- Enhanced monitoring capabilities

### 2. Legacy Compatibility
- Full compatibility with Joomla 1.0
- Support for legacy PHP MySQL extensions
- Maintains expected SQL behavior
- Preserves authentication methods

### 3. Future-Proofing
- Easy migration path to newer MySQL versions
- Modern character set support
- Better Unicode handling
- Improved error reporting

## Troubleshooting

### Common Issues

#### 1. Authentication Errors
If you encounter authentication errors:
```sql
-- Check user authentication plugin
SELECT user, host, plugin FROM mysql.user WHERE user = 'joomla';

-- Update user to use native password
ALTER USER 'joomla'@'%' IDENTIFIED WITH mysql_native_password BY 'joomlapassword';
FLUSH PRIVILEGES;
```

#### 2. SQL Mode Issues
If queries fail due to SQL mode:
```sql
-- Check current SQL mode
SELECT @@sql_mode;

-- Set session SQL mode to empty
SET SESSION sql_mode = '';
```

#### 3. Character Set Issues
If you encounter character encoding problems:
```sql
-- Check database character set
SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME 
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'joomla_test';

-- Update database character set if needed
ALTER DATABASE joomla_test CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

## Migration Notes

### From MySQL 8.0
- No breaking changes for Joomla 1.0
- Improved performance and stability
- Enhanced security features

### From MySQL 5.x
- Requires PHP MySQL extension updates
- May need to update connection strings
- Test all custom extensions thoroughly

## Security Considerations

While this configuration prioritizes compatibility, consider these security measures:

1. **Network Security**: Use Docker networks to isolate database access
2. **User Privileges**: Limit database user privileges to minimum required
3. **Regular Updates**: Keep MySQL container updated with security patches
4. **Backup Strategy**: Implement regular database backups
5. **Monitoring**: Monitor database logs for suspicious activity

## Performance Optimization

### Recommended Settings for Production
```sql
-- Increase buffer sizes for better performance
SET GLOBAL innodb_buffer_pool_size = 256M;
SET GLOBAL key_buffer_size = 64M;

-- Optimize query cache (if using older MySQL versions)
-- Note: Query cache removed in MySQL 8.0+
```

### Monitoring
- Monitor connection counts and query performance
- Use MySQL's performance schema for detailed analysis
- Set up slow query logging for optimization

## Conclusion

This MySQL 8.4 legacy mode configuration provides the best of both worlds: modern MySQL features and full compatibility with Joomla 1.0. The configuration is production-ready and provides a solid foundation for running legacy Joomla applications with modern infrastructure.
