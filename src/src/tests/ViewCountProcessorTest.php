<?php

namespace WikiLogs\Tests;

use PHPUnit\Framework\TestCase;
use WikiLogs\LogAnalyses;
use WikiLogs\ViewCountProcessor;
use Dotenv;
use PDO;
use ReflectionClass;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../lib/LogAnalyses.php';
require_once __DIR__ . '/../lib/ViewCountProcessor.php';

class ViewCountProcessorTest extends TestCase
{
  // PDOオブジェクトを格納するプロパティ
  private $pdo;

  public function setUp(): void
  {
    parent::setUp();
    // .envファイルのロード
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/lib');
    $dotenv->load();
    $logAnalyses = new LogAnalyses();
    $reflection = new ReflectionClass($logAnalyses);
    $method = $reflection->getMethod('connectDb');
    $method->setAccessible(true);
    $this->pdo = $method->invoke($logAnalyses);
  }

  public function testExecute()
  {
    $viewCountProcessor = new ViewCountProcessor();
    ob_start();
    $viewCountProcessor->execute($this->pdo);
    $output = ob_get_clean();
    $this->assertSame('表示させたい記事数の値(整数)を入力してください。' . PHP_EOL . '------------------------------' . PHP_EOL . 'en.m' . PHP_EOL . 'Main_Page' . PHP_EOL . '122058' . PHP_EOL . '------------------------------' . PHP_EOL, $output);
  }

  public function testGetArticleCount()
  {
    $viewCountProcessor = new ViewCountProcessor();
    $reflection = new ReflectionClass($viewCountProcessor);
    $method = $reflection->getMethod('getArticleCount');
    $method->setAccessible(true);
    $output = $method->invoke($viewCountProcessor);
    // 標準入力の値を期待値として設定
    $this->assertSame(1, $output);
  }
}
