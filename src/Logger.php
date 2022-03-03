<?php

declare(strict_types=1);

namespace Logger;

use Logger\Handlers\HandlerInterface;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{
    use LoggerImplTrait;

    /** @var HandlerInterface[] */
    private array $handlers;

    public function __construct(HandlerInterface ...$handlers)
    {
        $this->handlers = $handlers;
    }

    public function addHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
