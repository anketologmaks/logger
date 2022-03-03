<?php

declare(strict_types=1);

namespace Logger\Formatters;

use Logger\Record;

class LineFormatter implements FormatterInterface
{
    private const DEFAULT_FORMAT = '%date% %level_code% %level% %message%';
    private const DEFAULT_DATE_FORMAT = 'Y-m-d\TH:i:sP';
    private const LEVEL_CODE_FORMAT = '%03d';

    private string $format;
    private string $dateFormat;

    public function __construct(?string $format = null, ?string $dateFormat = null)
    {
        $this->format = $format ?? static::DEFAULT_FORMAT;
        $this->format .= PHP_EOL;

        $this->dateFormat = $dateFormat ?? static::DEFAULT_DATE_FORMAT;
    }

    public function format(Record $record): Record
    {
        $formattedMessage = $this->format;

        $vars = $record->data();

        foreach ($vars as $var => $val) {
            if (str_contains($formattedMessage, '%' . $var . '%')) {
                $formattedMessage = str_replace('%'.$var.'%', $this->transformToString($val, $var), $formattedMessage);
            }
        }

        $formatted = clone $record;
        $formatted->formatted = $formattedMessage;

        return $formatted;
    }

    private function transformToString(mixed $val, string $var): string
    {
        if (null === $val || is_bool($val)) {
            return var_export($val, true);
        }

        if (is_scalar($val)) {
            if ($var === Record::PARAM_LEVEL_CODE && is_int($val)) {
                return sprintf(self::LEVEL_CODE_FORMAT, $val);
            }

            return (string) $val;
        }

        if ($val instanceof \DateTimeInterface) {
            return $val->format($this->dateFormat);
        }

        return json_encode($val, JSON_THROW_ON_ERROR);
    }
}
