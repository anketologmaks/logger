<?php

declare(strict_types=1);

use Logger\Formatters\LineFormatter;
use Logger\Handlers\FakeHandler;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class LoggerTest extends TestCase
{
    /**
     * @dataProvider loggerProvider
     */
    public function testLoggerMethods(array $settings, $calledLogMethod, $calledMsg, $expectedResult)
    {
        $fakeHandler = new FakeHandler($settings);
        $logger = new Logger\Logger($fakeHandler);

        $logger->$calledLogMethod($calledMsg);

        $result = $fakeHandler->flush()[0];

        assertEquals($expectedResult, $result);
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
