<?php

namespace WikiLogs\Tests;

use PHPUnit\Framework\TestCase;
use WikiLogs\LogAnalyses;
use Dotenv;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/../lib/LogAnalyses.php');

class LogAnalysesTest extends TestCase
{
  public function setUp(): void
  {
    // .envファイルのロード
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/lib');
    $dotenv->load();
  }
  public function testStart()
  {
    $logAnalyses = new LogAnalyses();
    $this->assertSame(1, $logAnalyses->start());
  }


  public function testConnectDb()
  {
    // PDOオブジェクトのモックを作成
    $pdoMock = $this->createMock(PDO::class);

    // PDOモックにメソッドの返り値を設定
    $pdoMock->method('setAttribute')->willReturn(true);

    // クラスのインスタンス化
    $database = $this->getMockBuilder(LogAnalyses::class)
      ->onlyMethods(['connectDb'])
      ->getMock();

    // connectDbメソッドのモック設定
    $database->expects($this->once())
      ->method('connectDb')
      ->willReturn($pdoMock);

    // 実際にconnectDbメソッドを呼び出してテスト
    $this->assertSame($pdoMock, $database->connectDb());
  }
}
