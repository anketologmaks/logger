<?php

declare(strict_types=1);

namespace Test\Logger\Handlers;

use Logger\Formatters\LineFormatter;
use Logger\Handlers\FakeHandler;
use Logger\Handlers\FileHandler;
use Logger\Handlers\SyslogHandler;
use Logger\Logger;
use Logger\LogLevel;
use PHPUnit\Framework\TestCase;

class FileLoggerTest extends TestCase
{
    public function testSuccess(): void
    {
        $this->markTestSkipped();

        $handler = new FileHandler([
            'is_enabled' => true,
            'levels' => [
                LogLevel::LEVEL_ERROR,
            ],
            'filename' => __DIR__ . '/../../var/application.log',
            'formatter' => new LineFormatter(
                '%date%  [%level_code%]  [%level%]  %message%',
                'Y-m-d H:i:s'
            ),
        ]);

        $handler->handle(LogLevel::LEVEL_ERROR, 'MAKSIM');
        $handler->handle(LogLevel::LEVEL_ERROR, 'MAKSIM');
        $handler->handle(LogLevel::LEVEL_ERROR, 'MAKSIM');
    }
}
