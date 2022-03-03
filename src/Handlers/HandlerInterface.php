<?php

namespace Logger\Handlers;

interface HandlerInterface
{
    public function setIsEnabled(bool $enabled): void;

    public function isHandling(int $level): bool;

    public function handle(int|string $level, string $message);
}
