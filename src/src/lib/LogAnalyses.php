<?php

namespace WikiLogs;

use PDO;
use PDOException;
use Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

class LogAnalyses
{
  public function start()
  {
    try {
      $pdo = $this->connectDb();
    } catch (PDOException $e) {
      echo ' データベースの接続に失敗しました。' . PHP_EOL;
      echo 'Debugging Error:' . $e->getMessage() . PHP_EOL;
      return null;
    }
    $this->displayStartMessage();
    $result = $this->selectNumber();
    // 選択された番号に応じて処理を分岐
    return $result;
  }

  public function connectDb()
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
}
