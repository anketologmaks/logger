<?php

namespace Logger;

use Logger\Handlers\HandlerInterface;

trait LoggerImplTrait
{
    public function log($level, $message, array $context = []): void
    {
        $this->addRecord($level, (string)$message);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::LEVEL_ERROR, $message);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::LEVEL_NOTICE, $message);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::LEVEL_INFO, $message);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::LEVEL_DEBUG, $message);
    }

    public function addRecord(int|string $level, string $message): void
    {
        foreach ($this->getHandlers() as $handler) {
            $handler->handle($level, $message);
        }
    }

    public function emergency($message, array $context = []): void
    {
        throw new \LogicException('Level is not supported');
    }

    public function alert($message, array $context = []): void
    {
        throw new \LogicException('Level is not supported');
    }

    public function critical($message, array $context = []): void
    {
        throw new \LogicException('Level is not supported');
    }

    public function warning($message, array $context = []): void
    {
        throw new \LogicException('Level is not supported');
    }

    /**
     * @return HandlerInterface[]
     */
    abstract public function getHandlers(): array;
}
