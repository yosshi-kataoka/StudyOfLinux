<?php

namespace WikiLogs;

use WikiLogs\LogAnalyses;

require_once(__DIR__ . '/LogAnalyses.php');

$logAnalyses = new LogAnalyses();
$logAnalyses->start();
