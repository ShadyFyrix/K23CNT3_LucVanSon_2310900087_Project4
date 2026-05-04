@echo off
title PHP Server - UmaCT :8080
echo ===================================================
echo  PHP Built-in Server - UmaCT Frontend
echo  URL: http://localhost:8080
echo  Thu muc: Project4
echo ===================================================
echo.

:: Tim PHP theo thu tu uu tien
set PHP_EXE=

:: 1. Thu php co trong PATH khong
where php >nul 2>&1 && set PHP_EXE=php && goto :found

:: 2. XAMPP default
if exist "C:\xampp\php\php.exe" set PHP_EXE=C:\xampp\php\php.exe && goto :found

:: 3. WAMP64
if exist "C:\wamp64\bin\php\php8.2.0\php.exe" set PHP_EXE=C:\wamp64\bin\php\php8.2.0\php.exe && goto :found
if exist "C:\wamp64\bin\php\php8.1.0\php.exe" set PHP_EXE=C:\wamp64\bin\php\php8.1.0\php.exe && goto :found

:: 4. Laragon
if exist "C:\laragon\bin\php\php-8.2\php.exe" set PHP_EXE=C:\laragon\bin\php\php-8.2\php.exe && goto :found
if exist "C:\laragon\bin\php\php-8.1\php.exe" set PHP_EXE=C:\laragon\bin\php\php-8.1\php.exe && goto :found

echo [LOI] Khong tim thay PHP!
echo.
echo Vui long cai dat XAMPP: https://www.apachefriends.org/
echo Hoac mo XAMPP Control Panel va khoi dong Apache.
echo.
pause
exit /b 1

:found
echo [OK] Tim thay PHP tai: %PHP_EXE%
echo.
cd /d "d:\K23CNT3-LucVanSon-2310900087\K23CNT3_LucVanSon_2310900087_Project4"
echo Dang chay: %PHP_EXE% -S localhost:8080
echo.
%PHP_EXE% -S localhost:8080
pause
