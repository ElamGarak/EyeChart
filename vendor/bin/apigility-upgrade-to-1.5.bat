@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../zfcampus/zf-apigility-admin/bin/apigility-upgrade-to-1.5
php "%BIN_TARGET%" %*
