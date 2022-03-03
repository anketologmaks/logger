<?php

namespace Logger;

class LogLevel
{
    public const LEVEL_ERROR = 1;
    public const LEVEL_INFO = 2;
    public const LEVEL_DEBUG = 3;
    public const LEVEL_NOTICE = 4;

    public const NAMES = [
        self::LEVEL_ERROR => 'ERROR',
        self::LEVEL_INFO => 'INFO',
        self::LEVEL_DEBUG => 'DEBUG',
        self::LEVEL_NOTICE => 'NOTICE',
    ];
}
