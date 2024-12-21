<?php

namespace WikiLogs;

use PDO;

abstract class Processor
{
  abstract function execute(PDO $pdo);
}
