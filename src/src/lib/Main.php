<?php

namespace WikiLogs;

use WikiLogs\LogAnalysis;

require_once(__DIR__ . '/LogAnalysis.php');

$logAnalysis = new LogAnalysis();
$logAnalysis->start();
