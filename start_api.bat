@echo off
title FastAPI Server - UmaCT :8000
echo ===================================================
echo  FastAPI Backend - UmaCT API
echo  URL: http://127.0.0.1:8000
echo  Docs: http://127.0.0.1:8000/docs
echo ===================================================
echo.

:: Tim Python theo thu tu uu tien
set PY_EXE=

where python >nul 2>&1 && set PY_EXE=python && goto :found
where python3 >nul 2>&1 && set PY_EXE=python3 && goto :found

:: Path pho bien tren may nay (da xac nhan qua diagnose)
if exist "%LOCALAPPDATA%\Python\bin\python.exe" set PY_EXE=%LOCALAPPDATA%\Python\bin\python.exe && goto :found
if exist "%LOCALAPPDATA%\Programs\Python\Python314\python.exe" set PY_EXE=%LOCALAPPDATA%\Programs\Python\Python314\python.exe && goto :found

if exist "C:\Python312\python.exe" set PY_EXE=C:\Python312\python.exe && goto :found
if exist "C:\Python311\python.exe" set PY_EXE=C:\Python311\python.exe && goto :found
if exist "C:\Python310\python.exe" set PY_EXE=C:\Python310\python.exe && goto :found
if exist "C:\Python39\python.exe"  set PY_EXE=C:\Python39\python.exe  && goto :found

if exist "%LOCALAPPDATA%\Programs\Python\Python312\python.exe" set PY_EXE=%LOCALAPPDATA%\Programs\Python\Python312\python.exe && goto :found
if exist "%LOCALAPPDATA%\Programs\Python\Python311\python.exe" set PY_EXE=%LOCALAPPDATA%\Programs\Python\Python311\python.exe && goto :found
if exist "%LOCALAPPDATA%\Programs\Python\Python310\python.exe" set PY_EXE=%LOCALAPPDATA%\Programs\Python\Python310\python.exe && goto :found

echo [LOI] Khong tim thay Python!
echo.
echo Vui long cai Python tu: https://www.python.org/downloads/
echo Khi cai, tick chon: [x] Add Python to PATH
echo.
pause
exit /b 1

:found
echo [OK] Tim thay Python tai: %PY_EXE%
echo.
cd /d "d:\K23CNT3-LucVanSon-2310900087\K23CNT3_LucVanSon_2310900087_Project4\Project4-UmaCT-main\uma_api"

echo [1/2] Cai thu vien Python (neu chua co)...
%PY_EXE% -m pip install fastapi uvicorn pymysql cloudinary --quiet
if errorlevel 1 (
    echo [CANH BAO] pip install gap loi - thu tiep tuc...
)
echo.

echo [2/2] Khoi dong FastAPI...
echo Truy cap docs tai: http://127.0.0.1:8000/docs
echo Nhan Ctrl+C de dung.
echo.
%PY_EXE% -m uvicorn main:app --reload --port 8000
pause
