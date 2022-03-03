<?php

declare(strict_types=1);

namespace Test\Logger\Handlers;

use Logger\Formatters\LineFormatter;
use Logger\Handlers\AbstractHandler;
use Logger\LogLevel;
use Logger\Record;
use PHPUnit\Framework\TestCase;

class AbstractHandlerTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testSuccessHandle(array $settings, int|string $calledLevel, $expectedResult): void
    {
        $handler = new class($settings) extends AbstractHandler {
            public ?string $result = null;

            protected function write(Record $record): void
            {
                $this->result = $record->formatted;
            }
        };

        $handler->handle($calledLevel, 'success enabled dummy');

        self::assertEquals($expectedResult, $handler->result);
    }

    protected function dataProvider(): \Generator
    {
        $nowDate = (new \DateTimeImmutable())->format('Y-m-d');

        yield 'success enabled case' => [
            'settings' => [
                'is_enabled' => true,
                'formatter' => new LineFormatter(
                    '%date%  [%level_code%]  [%level%]  %message%',
                    'Y-m-d'
                ),
                'levels' => [
                    LogLevel::LEVEL_ERROR,
                ],
            ],
            'called level' => LogLevel::LEVEL_ERROR,
            'expectedMsg' => sprintf('%s  [001]  [ERROR]  success enabled dummy', $nowDate) . PHP_EOL
        ];

        yield 'disabled case' => [
            'settings' => [
                'is_enabled' => false,
                'levels' => [
                    LogLevel::LEVEL_ERROR,
                ],
            ],
            'called level' => LogLevel::LEVEL_ERROR,
            'expectedMsg' => null,
        ];

        yield 'log level mismatch and enabled' => [
            'settings' => [
                'is_enabled' => true,
                'levels' => [
                    LogLevel::LEVEL_NOTICE,
                ],
            ],
            'called level' => LogLevel::LEVEL_ERROR,
            'expectedMsg' => null,
        ];

        yield 'log level mismatch and disabled' => [
            'settings' => [
                'is_enabled' => false,
                'levels' => [
                    LogLevel::LEVEL_NOTICE,
                ],
            ],
            'called level' => LogLevel::LEVEL_ERROR,
            'expectedMsg' => null,
        ];

        yield 'success with blank levels' => [
            'settings' => [
                'is_enabled' => true,
                'formatter' => new LineFormatter(
                    '%date%  [%level_code%]  [%level%]  %message%',
                    'Y-m-d'
                ),
            ],
            'called level' => LogLevel::LEVEL_ERROR,
            'expectedMsg' => sprintf('%s  [001]  [ERROR]  success enabled dummy', $nowDate) . PHP_EOL
        ];

        yield 'success with custom levels' => [
            'settings' => [
                'is_enabled' => true,
                'formatter' => new LineFormatter(
                    '%date%  [%level_code%]  [%level%]  %message%',
                    'Y-m-d'
                ),
                'levels' => ['anyValue'],
            ],
            'called level' => 'anyValue',
            'expectedMsg' => null
        ];
    }

    /**
     * @dataProvider loggerProvider
     */
    public function testLoggerMethods(array $settings, $calledLogMethod, $calledMsg, $expectedResult)
    {
        $handler = new class($settings) extends AbstractHandler {
            public ?string $result = null;

            protected function write(Record $record): void
            {
                $this->result = $record->formatted;
            }
        };


        $handler->$calledLogMethod($calledMsg);

        self::assertEquals($expectedResult, $handler->result);
    }

    public function loggerProvider(): \Generator
    {
        $nowDate = (new \DateTimeImmutable())->format('Y-m-d');
        $settings = [
            'is_enabled' => true,
            'formatter' => new LineFormatter(
                '%date%  [%level_code%]  [%level%]  %message%',
                'Y-m-d'
            ),
        ];

        yield 'log' => [
            'settings' => $settings,
            'called method' => 'error',
            'called msg' => 'LOG BODY MESSAGE',
            'expectedMsg' => sprintf('%s  [001]  [ERROR]  LOG BODY MESSAGE', $nowDate) . PHP_EOL
        ];

        yield 'info' => [
            'settings' => $settings,
            'called method' => 'info',
            'called msg' => 'LOG BODY MESSAGE',
            'expectedMsg' => sprintf('%s  [002]  [INFO]  LOG BODY MESSAGE', $nowDate) . PHP_EOL
        ];

        yield 'debug' => [
            'settings' => $settings,
            'called method' => 'debug',
            'called msg' => 'LOG BODY MESSAGE',
            'expectedMsg' => sprintf('%s  [003]  [DEBUG]  LOG BODY MESSAGE', $nowDate) . PHP_EOL
        ];

        yield 'notice' => [
            'settings' => $settings,
            'called method' => 'notice',
            'called msg' => 'LOG BODY MESSAGE',
            'expectedMsg' => sprintf('%s  [004]  [NOTICE]  LOG BODY MESSAGE', $nowDate) . PHP_EOL
        ];
    }
}
