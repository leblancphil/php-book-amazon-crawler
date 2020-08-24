<?php

declare(strict_types=1);

namespace Src\Singleton;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{

  private const NAME = 'search';
  private const PATH = __DIR__ . '/../../logs/' . self::NAME . '.log';

  private static $instance = null;

  public static function get(): MonologLogger
  {
    if (self::$instance == null) {
      self::$instance = new MonologLogger(self::NAME);
      self::$instance->pushHandler(new StreamHandler(self::PATH, MonologLogger::DEBUG));
    }

    return self::$instance;
  }
}
