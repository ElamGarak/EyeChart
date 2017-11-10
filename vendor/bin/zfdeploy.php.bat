@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../zfcampus/zf-deploy/bin/zfdeploy.php
php "%BIN_TARGET%" %*
