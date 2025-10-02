@echo off
REM Joomla 1.0 Development Environment Startup Script for Windows

echo Starting Joomla 1.0 Development Environment...

REM Check if Docker is running
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Error: Docker is not running. Please start Docker first.
    pause
    exit /b 1
)

REM Build and start the containers
echo Building and starting Docker containers...
docker-compose up -d --build

REM Wait for services to be ready
echo Waiting for services to be ready...
timeout /t 10 /nobreak >nul

REM Check if services are running
echo Checking service status...
docker-compose ps

REM Install Composer dependencies
echo Installing Composer dependencies...
docker-compose exec joomla-test composer install

REM Run initial tests to verify setup
echo Running initial tests to verify setup...
docker-compose exec joomla-test ./vendor/bin/phpunit tests/unit/GlobalsTest.php

echo.
echo Development environment is ready!
echo.
echo Access points:
echo   - Joomla Site: http://localhost:8080
echo   - Admin Panel: http://localhost:8080/administrator
echo   - Database: localhost:3306 (user: joomla, password: joomlapassword)
echo   - Selenium: http://localhost:4444
echo.
echo Test commands:
echo   - Run all tests: docker-compose exec joomla-test composer test
echo   - Run unit tests: docker-compose exec joomla-test composer test-unit
echo   - Run with coverage: docker-compose exec joomla-test composer test-coverage
echo   - Code analysis: docker-compose exec joomla-test composer phpstan
echo   - Code style: docker-compose exec joomla-test composer phpcs
echo.
echo To stop the environment: docker-compose down
pause
