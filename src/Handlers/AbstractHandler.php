<?php

declare(strict_types=1);

namespace Logger\Handlers;

use Logger\Formatters\FormatterInterface;
use Logger\Formatters\LineFormatter;
use Logger\LoggerImplTrait;
use Logger\LogLevel;
use Logger\Record;
use Psr\Log\LoggerInterface;

abstract class AbstractHandler implements HandlerInterface, LoggerInterface
{
    use LoggerImplTrait;

    protected array $levels;
    protected bool $enabled = false;
    protected FormatterInterface $formatter;

    public function __construct(array $settings = [])
    {
        $this->enabled = $settings['is_enabled'] ?? false;
        $this->levels = $settings['levels'] ?? array_keys(LogLevel::NAMES);
        $this->formatter = $settings['formatter'] ?? new LineFormatter();
    }

    public function handle(int|string $level, string $message): void
    {
        [$levelName, $levelCode] = self::parseLevel($level);


        if (!$this->isHandling($levelCode)) {
            return;
        }

        $record = new Record(
            message: $message,
            level: $levelName,
            levelCode: $levelCode,
            date: new \DateTimeImmutable()
        );

        $record = $this->formatter->format($record);

        if ($record->formatted === null) {
            throw new \LogicException(
                sprintf('Bad formatted message {%s} with %s formatter',
                    $message,
                    get_debug_type($this->formatter))
            );
        }

        $this->write($record);
    }

    /**
     * Сделано допущение, что если в настройках хэндлера не указаны уровни логирования,
     * то работают не только все, но и кастомные (на лету)
     *
     * При том в лог за место названия уровня логирования попадет оригинальное значение level,
     * а вместо кода логирования будет '0'
     */
    public function isHandling(int|string $level): bool
    {
        return $this->enabled && in_array($level, $this->levels, true);
    }

    public function setIsEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    abstract protected function write(Record $record): void;

    public function getHandlers(): array
    {
        return [$this];
    }

    private static function parseLevel(int|string $level): array
    {
        if (is_int($level)) {
            return [LogLevel::NAMES[$level] ?? (string)$level, $level];
        }

        $code = array_search(strtolower($level), LogLevel::NAMES) ?: 0;

        return [$level, $code];
    }
}
