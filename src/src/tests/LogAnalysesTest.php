<?php

namespace WikiLogs\Tests;

use PHPUnit\Framework\TestCase;
use WikiLogs\LogAnalyses;
use WikiLogs\ViewCountProcessor;
use WikiLogs\DomainViewProcessor;
use Dotenv;
use PDO;
use ReflectionClass;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/../lib/LogAnalyses.php');
require_once(__DIR__ . '/../lib/ViewCountProcessor.php');
require_once(__DIR__ . '/../lib/DomainViewProcessor.php');

class LogAnalysesTest extends TestCase
{
  // PDOオブジェクトを格納するプロパティ
  private $pdo;
  // LogAnalysesオブジェクトを格納するプロパティ
  private $logAnalyses;

  public function setUp(): void
  {
    parent::setUp();
    // .envファイルのロード
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/lib');
    $dotenv->load();
    // PDOモックオブジェクトの作成
    $this->pdo = $this->createMock(PDO::class);
    $this->logAnalyses = new LogAnalyses();
  }
  public function testStart()
  {
    $this->assertTrue($this->logAnalyses->start());
  }

  // 下記テストでは、モックを使用しているため、ConnectDb()のアクセス権をprotectedに変更する必要がある
  public function testConnectDb()
  {
    // LogAnalysesオブジェクトの作成
    $logAnalyses = $this->getMockBuilder(LogAnalyses::class)->onlyMethods(['connectDb'])->getMock();
    // connectDbメソッドのモック設定
    $logAnalyses->method('connectDb')->willReturn($this->pdo);
    $reflection = new ReflectionClass($logAnalyses);
    $method = $reflection->getMethod('connectDb');
    $method->setAccessible(true);
    // privateメソッドのテスト
    $pdo = $method->invoke($logAnalyses);
    $this->assertInstanceOf(PDO::class, $pdo);
  }

  public function testSelectNumber()
  {
    $reflection = new ReflectionClass($this->logAnalyses);
    $method = $reflection->getMethod('selectNumber');
    $method->setAccessible(true);
    // 標準入力iを入力
    $inputNumber = $method->invoke($this->logAnalyses);
    $this->assertSame(1, $inputNumber);
    //　標準入力2を入力
    $inputNumber = $method->invoke($this->logAnalyses);
    $this->assertSame(2, $inputNumber);
  }

  public function testIsNotFloat()
  {
    $reflection = new ReflectionClass($this->logAnalyses);
    $method = $reflection->getMethod('isNotFloat');
    $method->setAccessible(true);
    $output = $method->invoke($this->logAnalyses, 1);
    $this->assertTrue($output);
    $output = $method->invoke($this->logAnalyses, 1.5);
    $this->assertFalse($output);
  }

  public function testIsOneOrTwo()
  {
    $reflection = new ReflectionClass($this->logAnalyses);
    $method = $reflection->getMethod('isOneOrTwo');
    $method->setAccessible(true);
    $output = $method->invoke($this->logAnalyses, 1);
    $this->assertTrue($output);
    $output = $method->invoke($this->logAnalyses, 2);
    $this->assertTrue($output);
    $output = $method->invoke($this->logAnalyses, 3);
    $this->assertFalse($output);
  }

  public function testGetProcessor()
  {
    $reflection = new ReflectionClass($this->logAnalyses);
    $method = $reflection->getMethod('getProcessor');
    $method->setAccessible(true);
    $output = $method->invoke($this->logAnalyses, 1);
    $this->assertSame(ViewCountProcessor::class, get_class($output));
    $output = $method->invoke($this->logAnalyses, 2);
    $this->assertSame(DomainViewProcessor::class, get_class($output));
  }
}
