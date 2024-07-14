<?php

namespace SimpleSAML\Module\rollbar\Logger;

use Rollbar\Payload\Level as RollbarLogLevel;
use Rollbar\Rollbar;
use SimpleSAML\Configuration;
use SimpleSAML\Logger;
use SimpleSAML\Logger\LoggingHandlerInterface;

/**
 * A class for logging to the default php error log.
 *
 * @package SimpleSAMLphp
 */
class RollbarLoggingHandler implements LoggingHandlerInterface {
  /**
   * Checks if the Rollbar is initialized.
   *
   * @var bool
   */
  private $isInitialized = FALSE;

  /**
   * This array contains the mappings from syslog log level to names.
   *
   * @var arrayintstring
   */
  private static array $levelMap = [
    Logger::EMERG   => RollbarLogLevel::EMERGENCY,
    Logger::ALERT   => RollbarLogLevel::ALERT,
    Logger::CRIT    => RollbarLogLevel::CRITICAL,
    Logger::ERR     => RollbarLogLevel::ERROR,
    Logger::WARNING => RollbarLogLevel::WARNING,
    Logger::NOTICE  => RollbarLogLevel::NOTICE,
    Logger::INFO    => RollbarLogLevel::INFO,
    Logger::DEBUG   => RollbarLogLevel::DEBUG,
  ];

  /**
   * The name of this process.
   *
   * @var string
   */
  private string $processname;

  /**
   * The Rollbar API token.
   *
   * @var string
   */
  private string $token;

  /**
   * The name of this envrionment.
   *
   * @var string
   */
  private string $environment;

  /**
   * ErrorLogLoggingHandler constructor.
   *
   * @param \SimpleSAML\Configuration $config
   *   The configuration object for this handler.
   */
  public function __construct(Configuration $config) {
    // Remove any non-printable characters before storing.
    $this->processname = preg_replace(
          '/[\x00-\x1F\x7F\xA0]/u',
          '',
          $config->getOptionalString('logging.processname', 'SimpleSAMLphp')
      );

    // Rollbar related configs.
    $this->token = $config->getOptionalString('rollbar.token', '');
    $this->environment = $config->getOptionalString('rollbar.environment', '');
  }

  /**
   * Initialize rollbar object.
   */
  protected function init() {
    if (empty($this->token) || empty($this->environment)) {
      return FALSE;
    }

    if (!$this->isInitialized) {
      Rollbar::init([
        'access_token' => $this->token,
        'environment' => $this->environment,
      ]);
      $this->isInitialized = TRUE;
    }

    return TRUE;
  }

  /**
   * Set the format desired for the logs.
   *
   * @param string $format
   *   The format used for logs.
   */
  public function setLogFormat(string $format): void {
    // We don't need the format here.
  }

  /**
   * Log a message to syslog.
   *
   * @param int $level
   *   The log level.
   * @param string $string
   *   The formatted message to log.
   */
  public function log(int $level, string $string): void {
    $levelName = self::$levelMap[$level] ?? sprintf('UNKNOWN%d', $level);
    if (!$this->init()) {
      return;
    }

    $formats = ['%process', '%level'];
    $replacements = [$this->processname, strtoupper($levelName)];
    $string = str_replace($formats, $replacements, $string);
    $string = preg_replace('/^%date(\{[^\}]+\})?\s*/', '', $string);
    $string = trim($string);

    Rollbar::log($levelName, $string);
  }

}
