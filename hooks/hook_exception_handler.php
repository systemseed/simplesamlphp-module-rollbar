<?php

/**
 * @file
 * Utilize hook provided by SimpleSAMLphp to log PHP exceptions.
 * @see https://simplesamlphp.org/docs/stable/simplesamlphp-modules.html#hook-interface
 */

declare(strict_types=1);

use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use SimpleSAML\Configuration;

/**
 * Hook to run a cron job.
 *
 * @param Throwable $exception
 *   The exception.
 *
 * @throws Throwable
 */
function rollbar_hook_exception_handler(Throwable $exception): void {
  // Check if Rollbar is configured to handle exceptions.
  $config = Configuration::getConfig();
  $enabled = $config->getOptionalBoolean('rollbar.exception_handler', FALSE);
  $token = $config->getOptionalString('rollbar.token', '');
  $environment = $config->getOptionalString('rollbar.environment', '');

  if ($enabled && $token && $environment) {
    // Initialize and configure the logger.
    Rollbar::init([
      'access_token' => $token,
      'environment' => $environment,
    ], FALSE, FALSE, FALSE);

    // Log the exception.
    Rollbar::logger()->report(Level::ERROR, $exception, isUncaught: TRUE);
  }
}
