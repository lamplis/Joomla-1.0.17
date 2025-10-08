#!/bin/bash

# Joomla 1.0 Development Environment Startup Script
# Run this from the REFACTO directory

echo "Starting Joomla 1.0 Development Environment from REFACTO directory..."

# Check if docker directory exists (either in current dir or parent)
if [ ! -d "docker" ]; then
    if [ ! -d "../docker" ]; then
        echo "Error: Cannot find docker directory"
        echo "Please ensure docker folder exists in project root"
        exit 1
    fi
    DOCKER_PATH="../docker"
else
    DOCKER_PATH="docker"
fi

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "Error: Docker is not running. Please start Docker first."
    exit 1
fi

# Build and start the containers
echo "Building and starting Docker containers..."
docker-compose -f $DOCKER_PATH/docker-compose.yml up -d --build

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 10

# Check if services are running
echo "Checking service status..."
docker-compose -f $DOCKER_PATH/docker-compose.yml ps

# Install Composer dependencies
echo "Installing Composer dependencies..."
docker-compose -f $DOCKER_PATH/docker-compose.yml exec joomla-test composer install

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
docker-compose -f $DOCKER_PATH/docker-compose.yml exec cypress npm install

# Run initial tests to verify setup
echo "Running initial tests to verify setup..."
docker-compose -f $DOCKER_PATH/docker-compose.yml exec joomla-test ./vendor/bin/phpunit tests/unit/GlobalsTest.php

echo ""
echo "Development environment is ready!"
echo ""
echo "Access points:"
echo "  - Joomla Site: http://localhost:8082"
echo "  - Admin Panel: http://localhost:8082/administrator"
echo "  - Mailpit UI: http://localhost:8025"
echo "  - Database: localhost:3307 (user: joomla, password: joomlapassword)"
echo ""
echo "Test commands:"
echo "  - Run all tests: npm run test:all"
echo "  - Run PHP unit tests: docker-compose -f $DOCKER_PATH/docker-compose.yml exec joomla-test composer test"
echo "  - Run Cypress tests: npm run test:e2e"
echo "  - Open Cypress UI: npm run test:e2e:open"
echo "  - Run with coverage: npm run test:coverage"
echo "  - Code analysis: docker-compose -f $DOCKER_PATH/docker-compose.yml exec joomla-test composer phpstan"
echo "  - Code style: docker-compose -f $DOCKER_PATH/docker-compose.yml exec joomla-test composer phpcs"
echo ""
echo "To stop the environment: docker-compose -f $DOCKER_PATH/docker-compose.yml down"
