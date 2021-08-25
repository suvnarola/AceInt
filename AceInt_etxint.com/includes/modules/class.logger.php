<?php

require __DIR__ . '/../../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

define('LOGPATH', '/home/etxint/applog/admin.etxint.com.log');

class ETXLogger {

  private static $logger;

  protected function __construct() {}
  public static function getLogger() {
    if (ETXLogger::$logger === null) {
      ETXLogger::$logger = new Logger('ETXLogger');
      ETXLogger::$logger->pushHandler(new StreamHandler(LOGPATH, Logger::INFO));
    }
    return ETXLogger::$logger;
  }

}