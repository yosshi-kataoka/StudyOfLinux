<?php

namespace WikiLogs\Tests;

use PHPUnit\Framework\TestCase;
use WikiLogs\LogAnalyses;

require_once(__DIR__ . '/../lib/LogAnalyses.php');

class LogAnalysesTest extends TestCase
{
  public function testStart()
  {
    $logAnalyses = new LogAnalyses();
    $this->assertSame(1, $logAnalyses->start());
  }
}
