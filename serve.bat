@echo off
REM Start Laravel development server and open Chrome (external)
cd /d "%~dp0"

REM Set PHP path
set PHP_PATH=C:\Users\Mega Providers\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe

REM Start server in background
start cmd /k "%PHP_PATH%" artisan serve --host=127.0.0.1 --port=8005

REM Wait for server to start
timeout /t 3 /nobreak

REM Try multiple Chrome paths
setlocal enabledelayedexpansion
set CHROME_PATH=

if exist "C:\Program Files\Google\Chrome\Application\chrome.exe" (
    set CHROME_PATH=C:\Program Files\Google\Chrome\Application\chrome.exe
) else if exist "C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" (
    set CHROME_PATH=C:\Program Files (x86)\Google\Chrome\Application\chrome.exe
)

if defined CHROME_PATH (
    echo Opening Chrome: !CHROME_PATH!
    start "" "!CHROME_PATH!" http://127.0.0.1:8005
) else (
    echo Chrome not found. Opening with default browser...
    start http://127.0.0.1:8005
)

echo.
echo Server started on http://127.0.0.1:8005
echo Press Ctrl+C in the server window to stop.
