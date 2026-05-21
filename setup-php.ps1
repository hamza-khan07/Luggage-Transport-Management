# Add PHP to PATH permanently for this PowerShell session and future sessions
$phpRoot = 'C:\Users\Mega Providers\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5_Microsoft.Winget.Source_8wekyb3d8bbwe'

# Add to current session PATH
if ($env:Path -notlike "*$phpRoot*") {
    $env:Path = "$phpRoot;$env:Path"
}

# Add to user environment PATH (permanent)
$currentPath = [Environment]::GetEnvironmentVariable('Path', 'User')
if ($currentPath -notlike "*$phpRoot*") {
    [Environment]::SetEnvironmentVariable('Path', "$phpRoot;$currentPath", 'User')
}

Write-Host "PHP path updated! You can now use 'php artisan serve'" -ForegroundColor Green
php -v
