@echo off
title Diagnostic - UmaCT Environment Check
echo ============================================
echo  UmaCT - Kiem tra moi truong he thong
echo ============================================
echo.

echo [1] Kiem tra PHP...
where php 2>nul && php -v | findstr "PHP" || echo PHP KHONG TIM THAY trong PATH
if exist "C:\xampp\php\php.exe" echo    -> Tim thay: C:\xampp\php\php.exe
if exist "C:\wamp64\bin\php\php8.2.0\php.exe" echo    -> Tim thay: C:\wamp64\bin\php\php8.2.0\php.exe
echo.

echo [2] Kiem tra Python...
where python 2>nul && python --version || echo PYTHON KHONG TIM THAY trong PATH
where python3 2>nul && python3 --version || echo.
if exist "C:\Python312\python.exe" echo    -> Tim thay: C:\Python312\python.exe
if exist "C:\Python311\python.exe" echo    -> Tim thay: C:\Python311\python.exe
if exist "C:\Python310\python.exe" echo    -> Tim thay: C:\Python310\python.exe
if exist "C:\Users\%USERNAME%\AppData\Local\Programs\Python\Python312\python.exe" echo    -> Tim thay: AppData\Python312
if exist "C:\Users\%USERNAME%\AppData\Local\Programs\Python\Python311\python.exe" echo    -> Tim thay: AppData\Python311
echo.

echo [3] Kiem tra pip...
where pip 2>nul && pip --version || echo PIP KHONG TIM THAY trong PATH
echo.

echo [4] Kiem tra FastAPI dang chay khong (port 8000)...
netstat -an | findstr ":8000" | findstr "LISTENING" && echo FastAPI DANG CHAY tai :8000 || echo FastAPI CHUA CHAY (port 8000 trong)
echo.

echo [5] Kiem tra MySQL/MariaDB...
netstat -an | findstr ":3306" | findstr "LISTENING" && echo MySQL DANG CHAY tai :3306 || echo MySQL CHUA CHAY (port 3306 trong)
echo.

echo [6] Thu ket noi FastAPI...
curl -s -o nul -w "HTTP Status: %%{http_code}" "http://127.0.0.1:8000/api/products" 2>nul || echo curl khong co san - khong the kiem tra
echo.
echo.
echo ============================================
echo  Ket qua chuan doan hoan tat
echo  Chup man hinh nay de de bao loi!
echo ============================================
pause
