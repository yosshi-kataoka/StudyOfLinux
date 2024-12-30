<?php

namespace WikiLogs;

use PDO;

class DomainViewProcessor extends Processor
{
  public function execute(PDO $pdo): void
  {
    $articleName = $this->getArticleName();
    $domainsArray = explode(' ', $articleName);
    // プレースホルダーを動的に生成
    $placeHolders = implode(',', array_fill(0, count($domainsArray), '?'));
    $statement = $pdo->prepare("SELECT domain_name, MAX(page_views) AS max_page_views FROM page_views WHERE domain_name IN ($placeHolders) GROUP BY domain_name ORDER BY max_page_views DESC");
    foreach ($domainsArray as $index => $name) {
      $statement->bindValue($index + 1, $name, PDO::PARAM_STR);
    }
    $statement->execute();

    while ($rows = $statement->fetch(PDO::FETCH_ASSOC)) {
      foreach ($rows as $row) {
        echo $row . PHP_EOL;
      }
      echo '------------------------------' . PHP_EOL;
    }
  }

  // 表示するドメイン名を標準入力より取得
  private function getArticleName(): string
  {
    while (true) {
      echo '表示させたいドメイン名を入力してください。(ドメイン名を半角スペースで区切ることで複数のドメイン名を入力可能です。)' . PHP_EOL;
      echo '入力例） en de ja' . PHP_EOL;
      echo '------------------------------' . PHP_EOL;
      $articleName = trim(fgets(STDIN));
      if ($this->isSpaceSeparated($articleName)) {
        break;
      } else {
        echo 'Error:ドメイン名が半角スペースで区切られておりません。' . PHP_EOL;
        echo 'ドメイン名は半角スペースで区切って入力してください。' . PHP_EOL;
      }
    }
    return $articleName;
  }

  // 入力値された文字列が半角スペースで区切られているかを確認する処理
  private function isSpaceSeparated($articleName): bool
  {
    return preg_match('/^[\S]+(?: [\S]+)*$/', $articleName);
  }
}
