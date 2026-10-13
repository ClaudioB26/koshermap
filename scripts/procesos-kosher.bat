@echo off
setlocal EnableExtensions EnableDelayedExpansion
chcp 65001 >nul

rem ============================================================
rem  KosherMap - procesos locales (scraping, limpieza, sync a produccion)
rem
rem  Uso:
rem    procesos-kosher.bat              -> menu interactivo
rem    procesos-kosher.bat completo     -> scrapers + cola + categorizar + limpiar + sync
rem    procesos-kosher.bat scrapers     -> solo scrapers rapidos + cola
rem    procesos-kosher.bat ou           -> OU Kosher (lento, retoma donde quedo)
rem    procesos-kosher.bat limpieza     -> categorizar + limpiar datos
rem    procesos-kosher.bat sync         -> envia todo a produccion
rem    procesos-kosher.bat sync-prueba  -> muestra que enviaria (no envia)
rem    procesos-kosher.bat lugares      -> Google Maps (max 2 ciudades por corrida)
rem    procesos-kosher.bat estado       -> chequea el sitio en produccion
rem    procesos-kosher.bat prueba       -> verifica que existan los comandos
rem
rem  Solo corre en LOCAL (SCRAPING_ENABLED=true). Los crons del servidor
rem  son otra cosa: ver doc/procesos.md
rem  Log: storage\logs\procesos-AAAAMMDD-HHMM.log
rem ============================================================

rem --- Raiz del proyecto (esta carpeta es scripts\) ---
pushd "%~dp0.."
set "ROOT=%CD%"

rem --- PHP 8.4 de Laragon (el PHP del PATH es 8.2 y el proyecto pide 8.4) ---
set "PHP="
for /d %%D in ("C:\laragon\bin\php\php-8.4*") do if exist "%%D\php.exe" set "PHP=%%D\php.exe"
if not defined PHP (
    echo [ERROR] No se encontro PHP 8.4 en C:\laragon\bin\php\
    popd & exit /b 1
)

rem --- Log ---
for /f %%i in ('powershell -nologo -noprofile -command "Get-Date -Format yyyyMMdd-HHmm"') do set "STAMP=%%i"
if not exist "storage\logs" mkdir "storage\logs"
set "LOG=%ROOT%\storage\logs\procesos-%STAMP%.log"
set "FALLOS=0"

if not "%~1"=="" (
    call :modo "%~1"
    set "RC=!ERRORLEVEL!"
    popd
    exit /b !RC!
)

:menu
echo.
echo ================= KosherMap - procesos =================
echo   1. COMPLETO  (scrapers + cola + categorizar + limpiar + sync)
echo   2. Solo scrapers rapidos (Ajdut, KMD, Kehila, Chile, UK, BDK) + cola
echo   3. OU Kosher (lento, retoma donde quedo)
echo   4. Solo limpieza y categorizacion
echo   5. Sincronizar con produccion
echo   6. Ver que se sincronizaria (sin enviar)
echo   7. Lugares Google Maps (max 2 ciudades)
echo   8. Estado del sitio en produccion
echo   9. Verificar comandos (prueba)
echo   0. Salir
echo ========================================================
set "OP="
set /p OP=Opcion:
if "%OP%"=="1" call :modo completo
if "%OP%"=="2" call :modo scrapers
if "%OP%"=="3" call :modo ou
if "%OP%"=="4" call :modo limpieza
if "%OP%"=="5" call :modo sync
if "%OP%"=="6" call :modo sync-prueba
if "%OP%"=="7" call :modo lugares
if "%OP%"=="8" call :modo estado
if "%OP%"=="9" call :modo prueba
if "%OP%"=="0" ( popd & exit /b 0 )
goto menu


rem ------------------------------------------------------------
:modo
set "M=%~1"
echo.
echo Log: %LOG%
echo.
if /i "%M%"=="completo"    ( call :scrapers & call :cola & call :limpieza & call :sync & goto :resumen )
if /i "%M%"=="scrapers"    ( call :scrapers & call :cola & goto :resumen )
if /i "%M%"=="ou"          ( call :ou & call :cola & goto :resumen )
if /i "%M%"=="limpieza"    ( call :limpieza & goto :resumen )
if /i "%M%"=="sync"        ( call :sync & goto :resumen )
if /i "%M%"=="sync-prueba" ( call :run "Sincronizacion (sin enviar)" sync:push --dry-run & goto :resumen )
if /i "%M%"=="lugares"     ( call :run "Google Maps: lugares kosher" scrape:places --limit=2 & goto :resumen )
if /i "%M%"=="estado"      ( call :estado & goto :resumen )
if /i "%M%"=="prueba"      ( call :prueba & goto :resumen )
echo Modo desconocido: %M%
exit /b 2

:resumen
echo.
if "%FALLOS%"=="0" (
    echo === Terminado sin errores ===
) else (
    echo === Terminado con %FALLOS% paso^(s^) con error. Revisar el log: %LOG% ===
)
exit /b %FALLOS%


rem ------------------------------------------------------------
:scrapers
call :run "Scraper Ajdut (Argentina)"        scrape:ajdut
call :run "Scraper KMD (Mexico)"             scrape:kmd
call :run "Scraper Kehila (Uruguay)"         scrape:kehila
call :run "Scraper Kosher Chile"             scrape:kosher-chile
call :run "Scraper UK Kosher (Latinoamerica)" scrape:uk-kosher
call :run "Scraper BDK Brasil"               scrape:bdk
exit /b 0

:ou
call :run "Scraper OU Kosher (retoma)"       scrape:ou-robust --resume
exit /b 0

:cola
rem Ajdut y OU encolan jobs: se procesan hasta vaciar la cola y se corta.
call :run "Procesar cola de jobs"            queue:work --stop-when-empty --timeout=300 --tries=3
exit /b 0

:limpieza
call :run "Categorizar BDK Brasil"           categorize:bdk
call :run "Categorizar productos por palabras clave" products:smart-categorize
call :run "Limpiar datos de scraping"        products:clean-data
exit /b 0

:sync
call :run "Sincronizar con produccion"       sync:push
exit /b 0

:estado
echo --- Estado del sitio ---
for %%U in (/ /articulos /productos /certifiers /sitemap.xml /css/app.css) do (
    for /f %%C in ('curl -s -o NUL -w "%%{http_code}" "https://koshermap.org%%U"') do echo   %%U  -^>  %%C
)
exit /b 0

:prueba
echo --- Comandos disponibles ---
for %%C in (scrape:ajdut scrape:kmd scrape:kehila scrape:kosher-chile scrape:uk-kosher scrape:bdk scrape:ou-robust scrape:places categorize:bdk products:smart-categorize products:clean-data sync:push tier:renewal-reminders tier:expire-overdue) do (
    "%PHP%" artisan list --raw 2>nul | findstr /b /c:"%%C " >nul && ( echo   OK   %%C ) || ( echo   FALTA %%C & set /a FALLOS+=1 )
)
call :run "Limpieza en modo prueba" products:clean-data --dry-run
call :run "Sincronizacion en modo prueba" sync:push --dry-run
exit /b 0


rem ------------------------------------------------------------
rem  :run "titulo" comando artisan [opciones...]
rem  Muestra la salida en pantalla Y la guarda en el log; cuenta los fallos.
:run
set "TITULO=%~1"
shift
set "ARGS="
:run_args
if "%~1"=="" goto run_go
set "ARGS=!ARGS! %~1"
shift
goto run_args
:run_go
echo.
echo ------------------------------------------------------------
echo  !TITULO!   [artisan!ARGS!]
echo ------------------------------------------------------------
echo. >> "%LOG%"
echo ==== !TITULO! [artisan!ARGS!] ==== >> "%LOG%"
powershell -nologo -noprofile -command "cmd /c '\"%PHP%\" artisan!ARGS! 2>&1' | Tee-Object -FilePath '%LOG%' -Append; exit $LASTEXITCODE"
if errorlevel 1 (
    echo [FALLO] !TITULO!
    echo [FALLO] !TITULO! >> "%LOG%"
    set /a FALLOS+=1
)
exit /b 0
