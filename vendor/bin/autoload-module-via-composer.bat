@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../zfcampus/zf-composer-autoloading/bin/autoload-module-via-composer
php "%BIN_TARGET%" %*
