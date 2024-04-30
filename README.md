## Introduction

SimpleSAMLphp + Rollbar

### simpleSAMLphp module

This module for SimpleSAMLphp provides a LoggerHandler that integrates with Rollbar service.

## Installation

1. Install simpleSAMLphp.
3. Install rollbar - `composer require systemseed/simplesamlphp-module-rollbar`.
4. Set `rollbar.token` value in `simplesamlphp/config/config.php`.
4. Set `rollbar.environment` value in `simplesamlphp/config/config.php`.
5. Set `logging.handler` to `'SimpleSAML\Module\rollbar\Logger\RollbarLoggingHandler'`.
6. Optionally set `logging.level` to `SimpleSAML\Logger::ERR` to send only errors.

