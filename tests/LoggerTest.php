<?php

declare(strict_types=1);

use Logger\Formatters\LineFormatter;
use Logger\Handlers\FakeHandler;
use Logger\Handlers\FileHandler;
use Logger\Handlers\SyslogHandler;
use Logger\Logger;
use Logger\LogLevel;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testFullCase(): void
    {
        $logger = new Logger();

        $FileHandler = new FileHandler(
            [
                'is_enabled' => true,
                'filename' => __DIR__ . '/../../var/application.log',
                'formatter' => new LineFormatter(),
            ]
        );

        $logger->addHandler($FileHandler);

        $logger->addHandler(
            new FileHandler(
                [
                    'is_enabled' => true,
                    'filename' => __DIR__ . '/../../var/application.error.log',
                    'levels' => [
                        LogLevel::LEVEL_ERROR,
                    ],
                    'formatter' => new LineFormatter(
                        '%date%  [%level_code%]  [%level%]  %message%',
                        'Y-m-d H:i:s'
                    ),
                ]
            )
        );

        $logger->addHandler(
            new FileHandler(
                [
                    'is_enabled' => true,
                    'filename' => __DIR__ . '/../../var/application.info.log',
                    'levels' => [
                        LogLevel::LEVEL_INFO,
                    ],
                    'formatter' => new LineFormatter(
                        '%date%  [%level_code%]  [%level%]  %message%',
                        'Y-m-d H:i:s'
                    ),
                ]
            )
        );

        $logger->addHandler(
            new SysLogHandler(
                [
                    'is_enabled' => true,
                    'levels' => [
                        LogLevel::LEVEL_DEBUG,
                    ],
                ]
            )
        );

        $logger->addHandler(
            new FakeHandler()
        );

        $logger->log(LogLevel::LEVEL_ERROR, 'Error message');
        $logger->error('Error message');

        $logger->log(LogLevel::LEVEL_INFO, 'Info message');
        $logger->info('Info message');

        $logger->log(LogLevel::LEVEL_DEBUG, 'Debug message');
        $logger->debug('Debug message');

        $logger->log(LogLevel::LEVEL_NOTICE, 'Notice message');
        $logger->notice('Notice message');


        $FileHandler->log(LogLevel::LEVEL_INFO, 'Info message from FileHandler');
        $FileHandler->info('Info message from FileHandler');

        $FileHandler->setIsEnabled(false);

        $FileHandler->log(LogLevel::LEVEL_INFO, 'Info message from FileHandler');
        $FileHandler->info('Info message from FileHandler');
    }
}
