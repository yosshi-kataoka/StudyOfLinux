<?php

namespace WikiLogs;

use PDO;

class ViewCountProcessor extends Processor
{
  public function execute(PDO $pdo): void
  {
    $articleCount = $this->getArticleCount();
    $statement = $pdo->prepare('SELECT * FROM page_views ORDER BY page_views DESC LIMIT :articleCount');
    $statement->bindParam(':articleCount', $articleCount, PDO::PARAM_INT);
    $statement->execute();
    while ($rows = $statement->fetch(PDO::FETCH_ASSOC)) {
      foreach ($rows as $row) {
        echo $row . PHP_EOL;
      }
      echo '------------------------------' . PHP_EOL;
    }
  }

  // 表示する記事の数を標準入力より取得
  private function getArticleCount(): int
  {
    while (true) {
      echo '表示させたい記事数の値(整数)を入力してください。' . PHP_EOL;
      echo '------------------------------' . PHP_EOL;
      $ArticleCount = trim(fgets(STDIN));
      if (is_numeric($ArticleCount) && $this->isNotFloat($ArticleCount)) {
        break;
      }
      echo 'Error:1以上の整数以外が入力されております。' . PHP_EOL;
    }
    return $ArticleCount;
  }

  private function isNotFloat($input): bool
  {
    if (strpos($input, '.') !== false) {
      return false;
    }
    return true;
  }
}
