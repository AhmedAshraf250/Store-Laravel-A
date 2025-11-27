# If not running as admin → restart script as admin using PowerShell 7
if (-NOT ([Security.Principal.WindowsPrincipal] `
    [Security.Principal.WindowsIdentity]::GetCurrent()
    ).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator"))
{
    $pwsh = "C:\Program Files\PowerShell\7\pwsh.exe"

    if (-Not (Test-Path $pwsh)) {
        Write-Host "PowerShell 7 not found at: $pwsh" -ForegroundColor Red
        pause
        exit
    }

    Start-Process $pwsh "-NoProfile -ExecutionPolicy Bypass -NoExit -File `"$PSCommandPath`"" -Verb RunAs
    exit
}

# Go to script directory
Set-Location -Path (Split-Path -Parent $MyInvocation.MyCommand.Definition)

Write-Host "Now running as ADMIN using PowerShell 7!" -ForegroundColor Green
Write-Host "Current Path: $PWD"

pause
