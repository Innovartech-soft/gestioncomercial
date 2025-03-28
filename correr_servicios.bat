@echo off
start /min cmd /k "cd /d D:\sistemaventas\hereledfront && npm run ng serve"
start /min cmd /k "cd /d D:\sistemaventas\gestioncomercialhereled && php artisan serve"