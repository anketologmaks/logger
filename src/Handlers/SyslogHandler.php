<?php

declare(strict_types=1);

namespace Logger\Handlers;

use Logger\Record;

class SyslogHandler extends AbstractHandler
{
    protected function write(Record $record): void
    {
        if (!openlog('local', LOG_PID, LOG_USER)) {
            throw new \LogicException('Can\'t open syslog for ident "local" and facility "'. LOG_USER .'"');
        }

        syslog($record->levelCode, (string) $record->formatted);
    }
}
