<?php

$postData = file_get_contents('php://input');
$logFile = 'output.json';

file_put_contents($logFile, "\n" . $postData, FILE_APPEND);