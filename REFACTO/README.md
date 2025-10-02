# Joomla 1.0 Refactoring Project

This folder contains all the testing infrastructure and refactoring tools for the Joomla 1.0 modernization project.

## 📁 Folder Structure

```
REFACTO/
├── README.md                 # This file
├── TESTING_STRATEGY.md       # Comprehensive testing strategy
├── docker-compose.yml        # Docker environment configuration
├── Dockerfile               # PHP 7.4 + Apache for Joomla 1.0
├── Dockerfile.test          # PHP 8.0 + testing tools
├── composer.json            # PHP dependencies and scripts
├── package.json             # Node.js dependencies for Cypress
├── phpunit.xml              # PHPUnit configuration
├── cypress.config.js        # Cypress configuration
├── start-dev.sh             # Linux/Mac startup script
├── start-dev.bat            # Windows startup script
├── docker/                  # Docker configuration files
│   └── mysql-init.sql       # Database initialization
├── tests/                   # PHPUnit tests
│   ├── bootstrap.php        # Test environment setup
│   ├── unit/                # Unit tests
│   ├── integration/         # Integration tests
│   ├── functional/          # Functional tests
│   └── helpers/             # Test helper classes
└── cypress/                 # Cypress end-to-end tests
    ├── e2e/                 # Test specifications
    ├── fixtures/            # Test data
    ├── support/             # Custom commands and configuration
    └── screenshots/         # Test screenshots (generated)
```

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose
- Node.js 16+ (for Cypress)
- PHP 7.4+ (for local development)

### 1. Start the Development Environment

**Windows:**
```bash
cd REFACTO
start-dev.bat
```

**Linux/Mac:**
```bash
cd REFACTO
chmod +x start-dev.sh
./start-dev.sh
```

### 2. Access Points
- **Joomla Site**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/administrator
- **Database**: localhost:3306 (user: joomla, password: joomlapassword)

### 3. Run Tests

**All Tests:**
```bash
npm run test:all
```

**PHP Unit Tests:**
```bash
docker-compose exec joomla-test composer test
```

**Cypress Tests:**
```bash
npm run test:e2e
```

**Open Cypress UI:**
```bash
npm run test:e2e:open
```

## 🧪 Testing Strategy

### Test Categories
1. **Unit Tests (80%)**: Individual functions and classes
2. **Integration Tests (15%)**: Component interactions
3. **Functional Tests (5%)**: End-to-end user workflows

### Coverage Goals
- **Minimum**: 90% line coverage
- **Target**: 95% overall coverage
- **Critical Areas**: 100% coverage (auth, content, security)

### Testing Tools
- **PHPUnit**: PHP unit and integration testing
- **Cypress**: Browser automation and E2E testing
- **PHPStan**: Static analysis
- **PHP CodeSniffer**: Code quality standards

## 📊 Coverage Reporting

Coverage reports are generated in the `coverage/` directory:
- **HTML Report**: `coverage/html/index.html`
- **Clover XML**: `coverage/clover.xml`
- **Text Report**: `coverage/coverage.txt`

## 🔧 Development Workflow

### 1. Test-Driven Development
1. Write failing test
2. Implement minimal code to pass
3. Refactor with tests passing
4. Repeat

### 2. Component Testing Priority
1. `com_content` (content management)
2. `com_user` (authentication)
3. `com_login` (login system)
4. `com_banners` (advertising)
5. Other components...

### 3. Module Testing Priority
1. `mod_login` (login module)
2. `mod_mainmenu` (navigation)
3. `mod_search` (search module)
4. Other modules...

## 🛠️ Custom Commands

### Cypress Commands
- `cy.loginAsAdmin()` - Login to admin panel
- `cy.loginAsUser(username, password)` - Login to frontend
- `cy.createTestContent(title, content)` - Create test content
- `cy.waitForJoomlaLoad()` - Wait for Joomla to load
- `cy.goToComponent(component)` - Navigate to component

### PHP Test Helpers
- `TestHelper::createTestUser()` - Create test user
- `TestHelper::createTestContent()` - Create test content
- `DatabaseHelper::insertTestData()` - Insert test data
- `DatabaseHelper::cleanupTestData()` - Clean up test data

## 📈 Progress Tracking

### Phase 1: Environment Setup ✅
- [x] Docker environment
- [x] Testing tools installation
- [x] Database setup

### Phase 2: Core Infrastructure ✅
- [x] Test base classes
- [x] Coverage analysis
- [x] CI/CD integration

### Phase 3: Component Testing (In Progress)
- [ ] Core components (com_content, com_user, etc.)
- [ ] Module testing
- [ ] Mambot testing

### Phase 4: Advanced Testing (Planned)
- [ ] Template testing
- [ ] Security testing
- [ ] Performance testing

### Phase 5: Final Validation (Planned)
- [ ] 100% coverage achievement
- [ ] Performance benchmarks
- [ ] Security validation

## 🚨 Important Notes

### Security Considerations
- Test database is isolated from production
- No real credentials in test environment
- All test data is cleaned up after tests

### Performance
- Tests run in parallel where possible
- Database transactions are rolled back
- Temporary files are cleaned up

### Maintenance
- Update tests when code changes
- Monitor coverage continuously
- Keep test documentation current

## 📞 Support

For questions or issues:
1. Check the `TESTING_STRATEGY.md` for detailed information
2. Review test examples in `tests/` and `cypress/e2e/`
3. Check Docker logs: `docker-compose logs`
4. Verify database connection: `docker-compose exec joomla-db mysql -u joomla -p joomla_test`

## 🎯 Next Steps

1. **Start Development Environment**: Run `start-dev.sh` or `start-dev.bat`
2. **Verify Setup**: Check that all services are running
3. **Run Initial Tests**: Execute `npm run test:all`
4. **Begin Component Testing**: Start with `com_content`
5. **Monitor Coverage**: Aim for 95%+ coverage before refactoring

---

## 🚀 **How to Start:**

### **Option 1: Quick Start (Recommended)**
```bash
# Windows
.\start-environment.bat
.\start-refacto.bat

# Linux/Mac
chmod +x start-refacto.sh
./start-refacto.sh
```

### **Option 2: Direct Access**
```bash
cd REFACTO
# Windows: start-environment.bat
# Linux/Mac: ./start-environment.sh
```

## 🧪 **Testing Commands:**
- **All Tests**: `npm run test:all`
- **PHP Tests**: `docker-compose exec joomla-test composer test`
- **Cypress Tests**: `npm run test:e2e`
- **Coverage**: `npm run test:coverage`

## 🎯 **Next Steps:**
1. **Start the environment** using the scripts above
2. **Verify setup** by checking all services are running
3. **Run initial tests** to confirm everything works
4. **Begin component testing** starting with `com_content`
5. **Monitor coverage** to achieve 95%+ before refactoring

