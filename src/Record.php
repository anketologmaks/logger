<?php

declare(strict_types=1);

namespace Logger;

class Record
{
    public const PARAM_MESSAGE = 'message';
    public const PARAM_LEVEL = 'level';
    public const PARAM_LEVEL_CODE = 'level_code';
    public const PARAM_DATE = 'date';

    public function __construct(
        public string $message,
        public string $level,
        public string|int $levelCode,
        public \DateTimeImmutable $date,
        public ?string $formatted = null,
        public array $extendedFields = []
    ) {
    }

    public function data(): array
    {
        $default = [
            self::PARAM_MESSAGE => $this->message,
            self::PARAM_LEVEL => $this->level,
            self::PARAM_LEVEL_CODE => $this->levelCode,
            self::PARAM_DATE => $this->date,
        ];

        return array_merge($default, $this->extendedFields);
    }
}
