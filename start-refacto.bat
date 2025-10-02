@echo off
REM Quick access script to start the REFACTO environment
REM Run this from the root Joomla directory

echo Starting Joomla 1.0 Refactoring Environment...

REM Check if REFACTO directory exists
if not exist "REFACTO" (
    echo Error: REFACTO directory not found
    pause
    exit /b 1
)

REM Change to REFACTO directory and run the environment
cd REFACTO
start-environment.bat
