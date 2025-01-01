<?php

namespace WikiLogs;

use PDO;
use PDOException;
use Dotenv;
use WikiLogs\Processor;
use WikiLogs\ViewCountProcessor;
use WikiLogs\DomainViewProcessor;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/Processor.php';
require_once __DIR__ . '/ViewCountProcessor.php';
require_once __DIR__ . '/DomainViewProcessor.php';

class LogAnalysis
{
  public function start(): bool
  {
    try {
      $pdo = $this->connectDb();
    } catch (PDOException $e) {
      echo ' データベースの接続に失敗しました。' . PHP_EOL;
      echo 'Debugging Error:' . $e->getMessage() . PHP_EOL;
      return null;
    }
    $this->displayStartMessage();
    $selectNumber = $this->selectNumber();
    // 選択された番号に応じて処理を分岐
    $processor = $this->getProcessor($selectNumber);
    $this->execute($processor, $pdo);
    return true;
  }

  protected function connectDb(): PDO
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $pdo = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] .  '; charset=utf8mb4', $_ENV['MYSQL_USER'],  $_ENV['MYSQL_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
  }

  private function displayStartMessage(): void
  {
    echo 'wikipediaのログを解析します。' . PHP_EOL;
  }

  private function selectNumber(): int
  {
    while (true) {
      echo '1または2を入力してください。' . PHP_EOL;
      echo '------------------------------' . PHP_EOL;
      echo '1:閲覧数の多い記事をランキング順に表示' . PHP_EOL;
      echo '2:ドメインごとの最も多い閲覧数を表示' . PHP_EOL;
      $input = trim(fgets(STDIN));
      if (is_numeric($input) && $this->isNotFloat($input)) {
        if ($this->isOneOrTwo($input)) {
          break;
        }
      }
      echo 'Error:入力値に1または2以外が入力されてます。' . PHP_EOL;
    }
    return $input;
  }

  private function isNotFloat($input): bool
  {
    if (strpos($input, '.') !== false) {
      return false;
    }
    return true;
  }

  private function isOneOrTwo(int $input): bool
  {
    if ($input === 1 || $input === 2) {
      return true;
    }
    return false;
  }

  // 入力値に応じて異なる表示処理を行うProcessorクラスをそれぞれ生成
  private function getProcessor(int $selectNumber): Processor
  {
    switch ($selectNumber) {
      case 1:
        return new ViewCountProcessor();
      case 2:
        return new DomainViewProcessor();
    }
  }

  private function execute(Processor $processor, PDO $pdo): void
  {
    $processor->execute($pdo);
  }
}
