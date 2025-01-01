<?php

namespace WikiLogs\Tests;

use PHPUnit\Framework\TestCase;
use WikiLogs\LogAnalysis;
use WikiLogs\ViewCountProcessor;
use WikiLogs\DomainViewProcessor;
use Dotenv;
use PDO;
use ReflectionClass;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/../lib/LogAnalysis.php');
require_once(__DIR__ . '/../lib/ViewCountProcessor.php');
require_once(__DIR__ . '/../lib/DomainViewProcessor.php');

class LogAnalysisTest extends TestCase
{
  // PDOオブジェクトを格納するプロパティ
  private $pdo;
  // LogAnalysesオブジェクトを格納するプロパティ
  private $logAnalysis;

  public function setUp(): void
  {
    parent::setUp();
    // .envファイルのロード
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/lib');
    $dotenv->load();
    // PDOモックオブジェクトの作成
    $this->pdo = $this->createMock(PDO::class);
    $this->logAnalysis = new LogAnalysis();
  }
  public function testStart()
  {
    $this->assertTrue($this->logAnalysis->start());
  }

  // 下記テストでは、モックを使用しているため、ConnectDb()のアクセス権をprotectedに変更する必要がある
  public function testConnectDb()
  {
    // LogAnalysesオブジェクトの作成
    $logAnalysis = $this->getMockBuilder(LogAnalysis::class)->onlyMethods(['connectDb'])->getMock();
    // connectDbメソッドのモック設定
    $logAnalysis->method('connectDb')->willReturn($this->pdo);
    $reflection = new ReflectionClass($logAnalysis);
    $method = $reflection->getMethod('connectDb');
    $method->setAccessible(true);
    // privateメソッドのテスト
    $pdo = $method->invoke($logAnalysis);
    $this->assertInstanceOf(PDO::class, $pdo);
  }

  public function testSelectNumber()
  {
    $reflection = new ReflectionClass($this->logAnalysis);
    $method = $reflection->getMethod('selectNumber');
    $method->setAccessible(true);
    // 標準入力iを入力
    $inputNumber = $method->invoke($this->logAnalysis);
    $this->assertSame(1, $inputNumber);
    //　標準入力2を入力
    $inputNumber = $method->invoke($this->logAnalysis);
    $this->assertSame(2, $inputNumber);
  }

  public function testIsNotFloat()
  {
    $reflection = new ReflectionClass($this->logAnalysis);
    $method = $reflection->getMethod('isNotFloat');
    $method->setAccessible(true);
    $output = $method->invoke($this->logAnalysis, 1);
    $this->assertTrue($output);
    $output = $method->invoke($this->logAnalysis, 1.5);
    $this->assertFalse($output);
  }

  public function testIsOneOrTwo()
  {
    $reflection = new ReflectionClass($this->logAnalysis);
    $method = $reflection->getMethod('isOneOrTwo');
    $method->setAccessible(true);
    $output = $method->invoke($this->logAnalysis, 1);
    $this->assertTrue($output);
    $output = $method->invoke($this->logAnalysis, 2);
    $this->assertTrue($output);
    $output = $method->invoke($this->logAnalysis, 3);
    $this->assertFalse($output);
  }

  public function testGetProcessor()
  {
    $reflection = new ReflectionClass($this->logAnalysis);
    $method = $reflection->getMethod('getProcessor');
    $method->setAccessible(true);
    $output = $method->invoke($this->logAnalysis, 1);
    $this->assertSame(ViewCountProcessor::class, get_class($output));
    $output = $method->invoke($this->logAnalysis, 2);
    $this->assertSame(DomainViewProcessor::class, get_class($output));
  }
}
