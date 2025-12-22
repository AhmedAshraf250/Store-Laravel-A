@echo off
:: Check if already admin
net session >nul 2>&1
if %errorLevel% == 0 (
    :: Already admin, open PowerShell 7 in project root
    pwsh.exe -NoExit -Command "Set-Location '%~dp0'; $Host.UI.RawUI.WindowTitle = 'Admin PowerShell 7 - %~dp0'"
) else (
    :: Not admin, request elevation
    powershell.exe -Command "Start-Process cmd.exe -ArgumentList '/c cd /d %~dp0 && pwsh.exe -NoExit -Command \"Set-Location ''%~dp0''; $Host.UI.RawUI.WindowTitle = ''Admin PowerShell 7 - %~dp0''\"' -Verb RunAs"
    exit
)