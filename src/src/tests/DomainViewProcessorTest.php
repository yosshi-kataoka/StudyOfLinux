<?php

namespace WikiLogs\Tests;

use PHPUnit\Framework\TestCase;
use WikiLogs\LogAnalysis;
use WikiLogs\DomainViewProcessor;
use Dotenv;
use PDO;
use ReflectionClass;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../lib/LogAnalysis.php';
require_once __DIR__ . '/../lib/DomainViewProcessor.php';

class DomainViewProcessorTest extends TestCase
{
  // PDOオブジェクトを格納するプロパティ
  private $pdo;

  public function setUp(): void
  {
    parent::setUp();
    // .envファイルのロード
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/lib');
    $dotenv->load();
    $logAnalysis = new LogAnalysis();
    $reflection = new ReflectionClass($logAnalysis);
    $method = $reflection->getMethod('connectDb');
    $method->setAccessible(true);
    $this->pdo = $method->invoke($logAnalysis);
  }

  public function testExecute()
  {
    $domainViewProcessor = new DomainViewProcessor();
    ob_start();
    $domainViewProcessor->execute($this->pdo);
    $output = ob_get_clean();
    // 'en' を入力
    $this->assertSame('表示させたいドメイン名を入力してください。(ドメイン名を半角スペースで区切ることで複数のドメイン名を入力可能です。)' . PHP_EOL . '入力例） en de ja' . PHP_EOL . '------------------------------' . PHP_EOL . 'en' . PHP_EOL . '69181' . PHP_EOL .  '------------------------------' . PHP_EOL, $output);
  }

  public function testGetArticleName()
  {
    $domainViewProcessor = new DomainViewProcessor();
    $reflection = new ReflectionClass($domainViewProcessor);
    $method = $reflection->getMethod('getArticleName');
    $method->setAccessible(true);
    $output = $method->invoke($domainViewProcessor);
    // 標準入力の値を期待値として設定
    $this->assertSame('en', $output);
  }

  public function testIsSpaceSeparated()
  {
    $domainViewProcessor = new DomainViewProcessor();
    $reflection = new ReflectionClass($domainViewProcessor);
    $method = $reflection->getMethod('isSpaceSeparated');
    $method->setAccessible(true);
    $output = $method->invoke($domainViewProcessor, 'en de');
    $this->assertTrue($output);
  }
}
