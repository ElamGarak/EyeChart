@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../zfcampus/zf-oauth2/bin/bcrypt.php
php "%BIN_TARGET%" %*
