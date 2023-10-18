<?php

namespace Api\Helpers;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class Logs
{
    private static string $channel = 'backend';
    private static string $logPatch = 'logs/backend.log';
    private static Logger $logger;
    private static int $numberOfFilesStored = 7;
    /**
     * Método estático que almacena un mensaje en el Log
     * @param $message
     * @param $modo
     * @return void
     */
    public static function logger($message, $modo = 'info'): void
    {
        self::$logger = new Logger(self::$channel);
        $handler = new RotatingFileHandler(self::$logPatch, self::$numberOfFilesStored);
        $dateFormat = 'j-n-Y, g:i:s a';
        $formatTemplate = "%datetime% > %level_name% > %message% %context% %extra%\n";
        $formatter = new LineFormatter($formatTemplate, $dateFormat);
        $handler->setFormatter($formatter);
        self::$logger->pushHandler($handler);

        switch ($modo) {
            case 'warning':
                self::$logger->warning($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            case 'error':
                self::$logger->error($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            case 'debug':
                self::$logger->debug($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            case 'notice':
                self::$logger->notice($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            case 'critical':
                self::$logger->critical($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            case 'alert':
                self::$logger->alert($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            case 'emergency':
                self::$logger->emergency($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
            default:
                self::$logger->info($message, array('ip' => $_SERVER['REMOTE_ADDR']));
                break;
        }
    }
}