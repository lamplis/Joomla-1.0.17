#!/bin/bash

# Joomla 1.0 Development Environment Startup Script

echo "Starting Joomla 1.0 Development Environment..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "Error: Docker is not running. Please start Docker first."
    exit 1
fi

# Build and start the containers
echo "Building and starting Docker containers..."
docker-compose up -d --build

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 10

# Check if services are running
echo "Checking service status..."
docker-compose ps

# Install Composer dependencies
echo "Installing Composer dependencies..."
docker-compose exec joomla-test composer install

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
docker-compose exec cypress npm install

# Run initial tests to verify setup
echo "Running initial tests to verify setup..."
docker-compose exec joomla-test ./vendor/bin/phpunit tests/unit/GlobalsTest.php

echo ""
echo "Development environment is ready!"
echo ""
echo "Access points:"
echo "  - Joomla Site: http://localhost:8080"
echo "  - Admin Panel: http://localhost:8080/administrator"
echo "  - Database: localhost:3306 (user: joomla, password: joomlapassword)"
echo ""
echo "Test commands:"
echo "  - Run all tests: npm run test:all"
echo "  - Run PHP unit tests: docker-compose exec joomla-test composer test"
echo "  - Run Cypress tests: npm run test:e2e"
echo "  - Open Cypress UI: npm run test:e2e:open"
echo "  - Run with coverage: npm run test:coverage"
echo "  - Code analysis: docker-compose exec joomla-test composer phpstan"
echo "  - Code style: docker-compose exec joomla-test composer phpcs"
echo ""
echo "To stop the environment: docker-compose down"
