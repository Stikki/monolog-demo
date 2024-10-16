<?php

require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\HostnameProcessor;

use Monolog\Formatter\SyslogFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;

$siteLog = new StreamHandler(__DIR__ . '/var/log/general.log', Level::Info);
$siteLog->setFormatter(new LineFormatter());

$generalLog = new StreamHandler(__DIR__ . '/var/log/localdev.log', Level::Info);
$generalLog->setFormatter(new SyslogFormatter());

$slackLog = new SlackWebhookHandler(
  webhookUrl: 'https://hooks.slack.com/services/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',
  level: Level::Critical
);
$slackLog->setFormatter(new SyslogFormatter());

$log = new Logger('stage');
$log->pushHandler($generalLog);
$log->pushHandler($siteLog);
$log->pushHandler($slackLog);

$log->pushProcessor(new PsrLogMessageProcessor());
$log->pushProcessor(new ProcessIdProcessor());
$log->pushProcessor(new HostnameProcessor());

$log->info("User {user} failed to log into server", ['user' => 'Stikki']);
//$log->critical("This level should only appear in slack");
