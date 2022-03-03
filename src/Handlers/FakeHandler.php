<?php

declare(strict_types=1);

namespace Logger\Handlers;

use Logger\Record;

class FakeHandler extends AbstractHandler
{
    public array $records = [];

    protected function write(Record $record): void
    {
        $this->records[] = $record->formatted;
    }

    public function flush(): array
    {
        $records = $this->records;
        $this->records = [];

        return $records;
    }
}
