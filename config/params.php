<?php

$env = YII_ENV;
$params = [];

$params['pdfFilePath'] = '/web/pdf-files';
$params['webPdfFilePath'] = '/pdf-files';
$params['webLogoFilePath'] = '/web/data';
$params['randomStringLength'] = 32;
$params['reportPath'] = 'tmp/report';
$params['reportRandomStringLength'] = 32;
$params['defaultTimezone'] = 'Asia/Jakarta';
$params['defaultUtc'] = 7;
$params['dateFormatDefault'] = 'DD-Mon-YY';
$params['dateFormatFullYearDefault'] = 'DD-Mon-YYYY';
$params['datetimeDisplayDefault'] = 'DD-Mon-YY HH24:MI';
$params['fullDatetimeDisplayDefault'] = 'DD-Mon-YY HH24:MI:SS';

return $params;
