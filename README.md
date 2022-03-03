
```phpt

<?php
/**
 * Задача:
 *
 * Без использования сторонних библиотек написать компонент
 * для логирования требующийся для запуска данного файла.
 * Компонент должен поддерживать разные обработчики (handlers):
 * логирование в файл (FileHandler), логирование в syslog (SysLogHandler),
 * логгер который ничего не делает (FakeHandler).
 *
 * Основные требования:
 * - аккуратность, чистота кода
 * - комментарии в коде для непонятных участков
 *
 * Результатом выполнение программы должно быть:
 * 2 записи в syslog (не обязательно, главное реализовать логгер) и 3 файла
 *
 * Файл application.log
 * *****************
 * 2016-05-30 09:50:57  001  ERROR  Error message
 * 2016-05-30 09:50:57  001  ERROR  Error message
 * 2016-05-30 09:50:57  002  INFO  Info message
 * 2016-05-30 09:50:57  002  INFO  Info message
 * 2016-05-30 09:50:57  003  DEBUG  Debug message
 * 2016-05-30 09:50:57  003  DEBUG  Debug message
 * 2016-05-30 09:50:57  004  NOTICE  Notice message
 * 2016-05-30 09:50:57  004  NOTICE  Notice message
 * 2016-05-30 09:50:57  002  INFO  Info message from FileHandler
 * 2016-05-30 09:50:57  002  INFO  Info message from FileHandler
 * *****************
 *
 * Файл application.error.log
 * *****************
 * 2016-05-30 09:50:57  [001]  [ERROR]  Error message
 * 2016-05-30 09:50:57  [001]  [ERROR]  Error message
 * *****************
 *
 * Файл application.info.log
 * *****************
 * 2016-05-30 09:50:57  [002]  [INFO]  Info message
 * 2016-05-30 09:50:57  [002]  [INFO]  Info message
 * *****************
 *
 * Формат записи в файл:
 * {дата} {код уровня логирования} {уровень логирования} {сообщение}
 */

/**
 * Компонент для логирования
 */
$logger = new Logger\Logger();

/**
 * Логер который все логи будет писать в файл application.log
 */
$FileHandler = new Logger\Handlers\FileHandler(
    [
        'is_enabled' => true,
        'filename' => __DIR__ . '/application.log',
        'formatter' => new Logger\Formatters\LineFormatter(),
    ]
);

$logger->addHandler($FileHandler);

/**
 * Логер который все ошибки будет писать в файл application.error.log
 */
$logger->addHandler(
    new Logger\Handlers\FileHandler(
        [
            'is_enabled' => true,
            'filename' => __DIR__ . '/application.error.log',
            'levels' => [
                Logger\LogLevel::LEVEL_ERROR,
            ],
            'formatter' => new Logger\Formatters\LineFormatter(
                '%date%  [%level_code%]  [%level%]  %message%',
                'Y-m-d H:i:s'
            ),
        ]
    )
);

/**
 * Логгер который все информационные логи будет писать в файл application.info.log
 */
$logger->addHandler(
    new Logger\Handlers\FileHandler(
        [
            'is_enabled' => true,
            'filename' => __DIR__ . '/application.info.log',
            'levels' => [
                Logger\LogLevel::LEVEL_INFO,
            ],
            'formatter' => new Logger\Formatters\LineFormatter(
                '%date%  [%level_code%]  [%level%]  %message%',
                'Y-m-d H:i:s'
            ),
        ]
    )
);

/**
 * Логгер который все debug логи записывает в syslog
 *
 * @see http://php.net/manual/ru/function.syslog.php
 */
$logger->addHandler(
    new Logger\Handlers\SysLogHandler(
        [
            'is_enabled' => true,
            'levels' => [
                Logger\LogLevel::LEVEL_DEBUG,
            ],
        ]
    )
);

/**
 * Логгер который ничего не делает
 */
$logger->addHandler(
    new Logger\Handlers\FakeHandler()
);

/**
 * Логирование
 */

$logger->log(Logger\LogLevel::LEVEL_ERROR, 'Error message');
$logger->error('Error message');

$logger->log(Logger\LogLevel::LEVEL_INFO, 'Info message');
$logger->info('Info message');

$logger->log(Logger\LogLevel::LEVEL_DEBUG, 'Debug message');
$logger->debug('Debug message');

$logger->log(Logger\LogLevel::LEVEL_NOTICE, 'Notice message');
$logger->notice('Notice message');


$FileHandler->log(Logger\LogLevel::LEVEL_INFO, 'Info message from FileHandler');
$FileHandler->info('Info message from FileHandler');

$FileHandler->setIsEnabled(false);

$FileHandler->log(Logger\LogLevel::LEVEL_INFO, 'Info message from FileHandler');
$FileHandler->info('Info message from FileHandler');

```
