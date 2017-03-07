@echo off

echo "copy build"
xcopy /s /e /h /y "\\gfxbench\Skynet\builds"

set localcl=""
for /r ..\default %%a in (CL*.txt) do (
   echo %%a
   for /f "tokens=2 delims=#" %%i in (%%a) do (set localcl=%%i)
   echo local build is: %localcl%
)

set servercl=""
for /r \\gfxbench\Skynet\builds %%b in (CL*.txt) do (
   echo %%b
   for /f "tokens=2 delims=#" %%i in (%%b) do (set servercl=%%i)
   echo server build is: %servercl%
)

if %localcl%==%servercl% (
    echo "build is the same"
    copy /y "..\default\*.ini"
) else (
    del /s /q "..\default\*.*"
    copy /y ".\*.ini" "..\default"
    copy /y ".\*.txt" "..\default"
)


