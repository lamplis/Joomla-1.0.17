# Joomla 1.0 Refactoring Project

[![Docker](https://img.shields.io/badge/Docker-Enabled-blue.svg)](https://www.docker.com/)
[![PHP](https://img.shields.io/badge/PHP-7.4%20%7C%208.4.13-777BB4.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1.svg)](https://www.mysql.com/)
[![Testing](https://img.shields.io/badge/Testing-PHPUnit%20%7C%20Cypress-4CAF50.svg)](https://phpunit.de/)
[![License](https://img.shields.io/badge/License-GPL%20v2-green.svg)](LICENSE.php)

## 🎯 Project Overview

This project provides a comprehensive testing and refactoring infrastructure for **Joomla 1.0**, a legacy CMS that requires modernization while maintaining 100% backward compatibility. The refactoring approach follows a **test-first methodology** to ensure zero functionality regression during the modernization process.

### Key Objectives
- **100% Code Coverage** before refactoring begins
- **Zero Regression** during modernization
- **Modern Development Environment** with Docker
- **Professional Testing Suite** with PHPUnit and Cypress
- **Email Testing Infrastructure** with Mailpit

## 📁 Project Architecture

```
Joomla-1.0/
├── [Original Joomla 1.0 files]     # Untouched legacy codebase
├── REFACTO/                        # Modern testing & refactoring tools
│   ├── docker/                     # Containerized development environment
│   │   ├── docker-compose.yml      # Multi-service orchestration
│   │   ├── Dockerfile              # PHP 7.4 + Apache (Joomla compatible)
│   │   ├── Dockerfile.test         # PHP 8.4.13 + testing tools
│   │   └── configs/                # Configuration files
│   ├── tests/                      # PHPUnit test suite
│   │   ├── unit/                   # Unit tests (80% target)
│   │   ├── integration/            # Integration tests (15% target)
│   │   ├── functional/             # Functional tests (5% target)
│   │   └── helpers/                # Test utilities
│   ├── cypress/                    # End-to-end browser tests
│   │   ├── e2e/                    # Test scenarios
│   │   └── support/                # Custom commands
│   ├── composer.json               # PHP dependencies
│   ├── phpunit.xml                 # PHPUnit configuration
│   ├── mysql_compat.php            # PHP 7.4+ compatibility layer
│   ├── start-refacto.sh            # Quick start (Linux/Mac)
│   └── start-refacto.bat           # Quick start (Windows)
```

## 🚀 Quick Start

### Prerequisites
- **Docker Desktop** (latest version)
- **Git** (for version control)
- **Node.js 18+** (for Cypress tests)

### 1. Clone and Start
```bash
# Clone the repository
git clone <repository-url>
cd Joomla-1.0

# Start the development environment
# Windows
REFACTO\start-refacto.bat

# Linux/Mac
chmod +x REFACTO/start-refacto.sh
./REFACTO/start-refacto.sh
```

### 2. Verify Installation
```bash
# Check all services are running
docker-compose -f docker/docker-compose.yml ps

# Access the application
# Joomla Site: http://localhost:8082
# Admin Panel: http://localhost:8082/administrator
# Mailpit UI: http://localhost:8025
```

### 3. Run Initial Tests
```bash
# Navigate to REFACTO directory
cd REFACTO

# Install dependencies
composer install
npm install

# Run all tests
npm run test:all
```

## 🗄️ Database Configuration

### Default Database Credentials
For quick setup, the following default credentials are configured:

| Setting | Value | Description |
|---------|-------|-------------|
| **Database Host** | `joomla-db` (Docker) / `localhost:3307` (External) | MySQL server |
| **Database Name** | `joomla_test` | Default database name |
| **Username** | `joomla` | Database user |
| **Password** | `joomlapassword` | Database password |
| **Port** | `3307` | External access port |

### Database Setup
The database is automatically configured with:
- **MySQL 8.0** with legacy compatibility
- **Relaxed SQL mode** for Joomla 1.0 compatibility
- **Native password authentication** for older PHP versions
- **Pre-created database** and user with proper permissions

### Manual Database Connection
If you need to connect externally:

```bash
# Connect via command line
mysql -h localhost -P 3307 -u joomla -pjoomlapassword joomla_test

# Or update your Joomla configuration
# Edit configuration.php with these values:
$mosConfig_host = 'localhost:3307';
$mosConfig_user = 'joomla';
$mosConfig_password = 'joomlapassword';
$mosConfig_db = 'joomla_test';
```

## 🏗️ Development Environment

### Docker Services

| Service | Technology | Port | Purpose |
|---------|------------|------|---------|
| **joomla-web** | PHP 7.4 + Apache | 8082 | Joomla 1.0 compatible web server |
| **joomla-db** | MySQL 8.0 | 3307 | Database with legacy compatibility |
| **joomla-test** | PHP 8.4.13 + Tools | - | Testing environment with latest tools |
| **cypress** | Node.js + Browsers | - | End-to-end testing |
| **mailpit** | SMTP Server | 8025/1025 | Email testing and debugging |

### Environment Features
- **PHP Compatibility Layer**: Automatic polyfills for deprecated functions
- **MySQL Legacy Support**: Relaxed SQL modes for Joomla 1.0 compatibility
- **Email Testing**: All `mail()` calls captured in Mailpit UI
- **Session Management**: Proper session handling for authentication
- **Hot Reloading**: Code changes reflected immediately

## 🧪 Testing Strategy

### Testing Philosophy
This project follows a **comprehensive test-first approach** to ensure safe refactoring:

1. **Establish Baseline**: Create tests for existing functionality
2. **Achieve Coverage**: Reach 100% code coverage
3. **Refactor Safely**: Modernize code with confidence
4. **Validate Continuously**: Ensure no regression

### Test Distribution
- **Unit Tests (80%)**: Individual functions, classes, and methods
- **Integration Tests (15%)**: Component interactions and data flow
- **Functional Tests (5%)**: End-to-end user workflows

### Testing Tools

#### PHP Testing Stack
- **PHPUnit 10.5**: Modern PHP testing framework
- **PHPStan**: Static analysis for code quality
- **PHP CodeSniffer**: PSR-12 compliance checking
- **PHPCPD**: Copy-paste detection

#### Browser Testing Stack
- **Cypress**: Modern end-to-end testing
- **Multiple Browsers**: Chrome, Firefox, Edge support
- **Visual Testing**: Screenshot comparison capabilities

#### Email Testing
- **Mailpit**: SMTP server with web UI
- **Message Capture**: All emails intercepted and displayed
- **No External Dependencies**: Self-contained email testing

## 📊 Testing Commands

### Comprehensive Testing
```bash
# Run all test suites
npm run test:all

# Generate coverage report
npm run test:coverage

# Run tests in watch mode
npm run test:watch
```

### PHP Testing
```bash
# Unit tests only
docker-compose -f docker/docker-compose.yml exec joomla-test composer test-unit

# Integration tests only
docker-compose -f docker/docker-compose.yml exec joomla-test composer test-integration

# All PHP tests with coverage
docker-compose -f docker/docker-compose.yml exec joomla-test composer test-coverage

# Static analysis
docker-compose -f docker/docker-compose.yml exec joomla-test composer phpstan

# Code style checking
docker-compose -f docker/docker-compose.yml exec joomla-test composer phpcs
```

### Browser Testing
```bash
# Run Cypress tests
npm run test:e2e

# Open Cypress Test Runner
npm run cypress:open

# Run specific test file
npx cypress run --spec "cypress/e2e/admin-login.cy.js"
```

### Email Testing
```bash
# Access Mailpit UI
open http://localhost:8025

# Send test email via PHP
docker-compose -f docker/docker-compose.yml exec joomla-web php -r "mail('test@example.com', 'Test Subject', 'Test Body');"
```

## 🎯 Refactoring Roadmap

### Phase 1: Foundation ✅
- [x] Docker environment setup
- [x] Testing framework installation
- [x] Database configuration with legacy support
- [x] Email testing infrastructure
- [x] PHP compatibility layer

### Phase 2: Test Coverage (Current)
- [ ] Core components testing (`com_content`, `com_user`, etc.)
- [ ] Module testing (`mod_mainmenu`, `mod_login`, etc.)
- [ ] Mambot testing (content filters, editors)
- [ ] Template testing (frontend and admin)
- [ ] Database layer testing

### Phase 3: Coverage Achievement
- [ ] 95%+ overall code coverage
- [ ] 100% critical path coverage
- [ ] Performance benchmarks establishment
- [ ] Security validation
- [ ] Regression test suite completion

### Phase 4: Safe Refactoring
- [ ] Modern PHP patterns implementation
- [ ] Security improvements
- [ ] Performance optimization
- [ ] Code quality enhancement
- [ ] Documentation updates

## 🔧 Configuration

### Database Configuration
The project uses MySQL 8.0 with legacy compatibility settings:

```sql
-- Relaxed SQL mode for Joomla 1.0 compatibility
SET GLOBAL sql_mode='';

-- Native password authentication
ALTER USER 'joomla'@'%' IDENTIFIED WITH mysql_native_password BY 'joomlapassword';
```

### PHP Configuration
PHP 7.4 configured for Joomla 1.0 compatibility:

```ini
# Legacy compatibility settings
register_globals = Off
magic_quotes_gpc = Off
session.auto_start = Off
short_open_tag = On

# Performance settings
memory_limit = 128M
upload_max_filesize = 32M
max_execution_time = 300
```

### Email Configuration
All emails are captured by Mailpit for testing:

```ini
# msmtp configuration
sendmail_path = /usr/bin/msmtp -t
```

## 🔒 Security & Safety

### Test Environment Isolation
- **Separate Test Database**: No production data exposure
- **Isolated Containers**: Complete environment isolation
- **Automatic Cleanup**: Temporary files and data removed after tests
- **No External Dependencies**: Self-contained testing environment

### Data Protection
- **Test Data Only**: No real user credentials or sensitive data
- **Database Transactions**: Automatic rollback after tests
- **File System Isolation**: Temporary directories for test files
- **Network Isolation**: No external network access during tests

## 📈 Progress Tracking

### Current Status
- ✅ **Environment Setup**: Complete
- ✅ **Testing Framework**: Complete
- ✅ **Email Infrastructure**: Complete
- 🔄 **Component Testing**: In Progress
- ⏳ **Coverage Achievement**: Planned
- ⏳ **Refactoring**: Planned

### Success Metrics
- **Code Coverage**: Target 95%+ overall, 100% critical paths
- **Test Execution Time**: < 5 minutes for full suite
- **Zero Regressions**: All existing functionality preserved
- **Performance**: No degradation in response times

## 🆘 Troubleshooting

### Common Issues

#### Docker Issues
```bash
# Check Docker is running
docker --version
docker-compose --version

# Restart services
docker-compose -f docker/docker-compose.yml down
docker-compose -f docker/docker-compose.yml up -d

# View logs
docker-compose -f docker/docker-compose.yml logs joomla-web
```

#### Port Conflicts
```bash
# Check port usage
netstat -an | findstr :8082
netstat -an | findstr :3307
netstat -an | findstr :8025

# Stop conflicting services
# Update ports in docker/docker-compose.yml if needed
```

#### Database Connection Issues
```bash
# Test database connection
docker-compose -f docker/docker-compose.yml exec joomla-web php -r "
\$link = mysqli_connect('joomla-db', 'joomla', 'joomlapassword', 'joomla_test');
if (\$link) echo 'Connected successfully'; else echo 'Connection failed';
"
```

#### PHP Compatibility Issues
```bash
# Check PHP compatibility layer
docker-compose -f docker/docker-compose.yml exec joomla-web php -r "
echo 'mysql_connect: ' . (function_exists('mysql_connect') ? 'OK' : 'MISSING') . PHP_EOL;
echo 'ereg: ' . (function_exists('ereg') ? 'OK' : 'MISSING') . PHP_EOL;
"
```

### Getting Help
1. **Check Logs**: `docker-compose -f docker/docker-compose.yml logs [service]`
2. **Verify Services**: `docker-compose -f docker/docker-compose.yml ps`
3. **Test Connectivity**: Use the troubleshooting commands above
4. **Review Documentation**: Check this README and inline comments

## 📚 Documentation

### Key Documentation Files
- **README.md**: This comprehensive guide
- **docker/docker-compose.yml**: Service configuration
- **REFACTO/phpunit.xml**: PHPUnit test configuration
- **REFACTO/cypress.config.js**: Cypress test configuration
- **REFACTO/composer.json**: PHP dependencies and scripts

### Example Test Files
- **REFACTO/tests/unit/**: Unit test examples
- **REFACTO/cypress/e2e/**: End-to-end test examples
- **REFACTO/tests/helpers/**: Test utility examples

## 🎉 Success Criteria

### Before Refactoring
- [ ] 95%+ overall code coverage
- [ ] 100% critical path coverage
- [ ] All tests passing consistently
- [ ] Performance benchmarks established
- [ ] Security validation complete
- [ ] Email functionality verified

### After Refactoring
- [ ] All tests still passing
- [ ] Zero functionality regression
- [ ] Improved code quality metrics
- [ ] Enhanced security posture
- [ ] Better performance characteristics
- [ ] Modern PHP patterns implemented

## 🤝 Contributing

### Development Workflow
1. **Fork and Clone**: Create your development branch
2. **Start Environment**: Use `start-refacto.bat` or `start-refacto.sh`
3. **Write Tests**: Create tests for new functionality
4. **Implement**: Write code to pass tests
5. **Validate**: Run full test suite
6. **Submit**: Create pull request with test coverage

### Code Standards
- **PSR-12**: PHP coding standards
- **Test Coverage**: New code must have tests
- **Documentation**: Update README for significant changes
- **Backward Compatibility**: Maintain Joomla 1.0 compatibility

## 📄 License

This project maintains the original Joomla 1.0 GPL v2 license. See [LICENSE.php](LICENSE.php) for details.

---

## 🚀 Ready to Start?

**Quick Start Command:**
```bash
# Windows
REFACTO\start-refacto.bat

# Linux/Mac
chmod +x REFACTO/start-refacto.sh && ./REFACTO/start-refacto.sh
```

**Access Points:**
- **Joomla Site**: http://localhost:8082
- **Admin Panel**: http://localhost:8082/administrator
- **Mailpit UI**: http://localhost:8025
- **Database**: localhost:3307

**Remember**: The goal is to achieve comprehensive test coverage before beginning any refactoring work. This ensures that all functionality is preserved during the modernization process.

---

*Built with ❤️ for the Joomla community*