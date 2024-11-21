<?php

namespace WikiLogs;

class LogAnalyses
{
  public function start()
  {
    $this->displayStartMessage();
    $result = $this->selectNumber();
    // 選択された番号に応じて処理を分岐
    return $result;
  }

  private function displayStartMessage(): void
  {
    echo 'wikipediaのログを解析します。' . PHP_EOL;
    echo '1または2を入力してください。' . PHP_EOL;
    echo '------------------------------' . PHP_EOL;
  }

  private function selectNumber(): int
  {
    echo '1:閲覧数の多い記事をランキング順に表示' . PHP_EOL;
    echo '2:ドメインごとの最も多い閲覧数を表示' . PHP_EOL;
    return 1;
  }
}
