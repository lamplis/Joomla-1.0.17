#!/bin/bash

# Quick access script to start the REFACTO environment
# Run this from the root Joomla directory

echo "Starting Joomla 1.0 Refactoring Environment..."

# Check if REFACTO directory exists
if [ ! -d "REFACTO" ]; then
    echo "Error: REFACTO directory not found"
    exit 1
fi

# Change to REFACTO directory and run the environment
cd REFACTO
chmod +x start-environment.sh
./start-environment.sh
