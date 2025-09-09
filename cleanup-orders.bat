@echo off
echo Limpiando pre-ordenes no pagadas...
php artisan orders:auto-clean
echo.
echo Limpieza completada. Ejecuta este archivo cada 10 minutos para mantener la BD limpia.
pause

