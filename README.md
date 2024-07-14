## Introduction

SimpleSAMLphp + Rollbar

### SimpleSAMLphp module

This module for SimpleSAMLphp provides a LoggerHandler that integrates with Rollbar service.

## Installation

- Install and configure SimpleSAMLphp.
- Install Rollbar - `composer require systemseed/simplesamlphp-module-rollbar`.

## Enable Rollbar

- Fill `rollbar.token` value in `simplesamlphp/config/config.php` with server token taken from Rollbar.
- Set `rollbar.environment` value in `simplesamlphp/config/config.php` to define current environment name.

### Configure Rollbar to handle PHP exceptions

Set `rollbar.exception_handler` value to `true` in `simplesamlphp/config/config.php`. This will catch and log all
exceptions to Rollbar as a single occurrence in Rollbar dashboard.

### Configure Rollbar to handle all log items

Set `logging.handler` to `'SimpleSAML\Module\rollbar\Logger\RollbarLoggingHandler'`. Please be aware that enabled
backtraces, will result in each line in the backtrace treated as a separate occurrence.
