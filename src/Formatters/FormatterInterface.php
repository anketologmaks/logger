<?php

declare(strict_types=1);

namespace Logger\Formatters;

use Logger\Record;

interface FormatterInterface
{
    public function format(Record $record): Record;
}
