@echo off
REM Joomla 1.0 Development Environment Startup Script for Windows
REM Run this from the REFACTO directory

echo Starting Joomla 1.0 Development Environment from REFACTO directory...

REM Check if docker directory exists (either in current dir or parent)
if not exist "docker" (
    if not exist "..\docker" (
        echo Error: Cannot find docker directory
        echo Please ensure docker folder exists in project root
        pause
        exit /b 1
    )
    set DOCKER_PATH=..\docker
) else (
    set DOCKER_PATH=docker
)

REM Check if Docker is running
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Error: Docker is not running. Please start Docker first.
    pause
    exit /b 1
)

REM Build and start the containers
echo Building and starting Docker containers...
docker-compose -f %DOCKER_PATH%/docker-compose.yml up -d --build

REM Wait for services to be ready
echo Waiting for services to be ready...
timeout /t 10 /nobreak >nul

REM Check if services are running
echo Checking service status...
docker-compose -f %DOCKER_PATH%/docker-compose.yml ps

REM Install Composer dependencies
echo Installing Composer dependencies...
docker-compose -f %DOCKER_PATH%/docker-compose.yml exec joomla-test composer install

REM Install Node.js dependencies
echo Installing Node.js dependencies...
docker-compose -f %DOCKER_PATH%/docker-compose.yml exec cypress npm install

REM Run initial tests to verify setup
echo Running initial tests to verify setup...
docker-compose -f %DOCKER_PATH%/docker-compose.yml exec joomla-test ./vendor/bin/phpunit tests/unit/GlobalsTest.php

echo.
echo Development environment is ready!
echo.
echo Access points:
echo   - Joomla Site: http://localhost:8082
echo   - Admin Panel: http://localhost:8082/administrator
echo   - Mailpit UI: http://localhost:8025
echo   - Database: localhost:3307 (user: joomla, password: joomlapassword)
echo.
echo Test commands:
echo   - Run all tests: npm run test:all
echo   - Run PHP unit tests: docker-compose -f %DOCKER_PATH%/docker-compose.yml exec joomla-test composer test
echo   - Run Cypress tests: npm run test:e2e
echo   - Open Cypress UI: npm run test:e2e:open
echo   - Run with coverage: npm run test:coverage
echo   - Code analysis: docker-compose -f %DOCKER_PATH%/docker-compose.yml exec joomla-test composer phpstan
echo   - Code style: docker-compose -f %DOCKER_PATH%/docker-compose.yml exec joomla-test composer phpcs
echo.
echo To stop the environment: docker-compose -f %DOCKER_PATH%/docker-compose.yml down
pause
