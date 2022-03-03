<?php

declare(strict_types=1);

namespace Logger\Handlers;

use Logger\Record;

class FileHandler extends AbstractHandler
{
    private string $filename;

    public function __construct(array $settings = [])
    {
        if (!isset($settings['filename'])) {
            throw new \LogicException('Please define param: `filename` for FileHandler');
        }

        $this->filename = (string)$settings['filename'];

        parent::__construct($settings);
    }

    protected function write(Record $record): void
    {
        $paths = pathinfo($this->filename);
        $dirname = $paths['dirname'];

        $descr = null;

        if (!is_resource($this->filename)) {
            $this->createDir($dirname);

            $descr = fopen($this->filename, 'a');
            chmod($this->filename, 0644);

            if (!is_resource($descr)) {
                throw new \UnexpectedValueException(sprintf('The stream or file "%s" could not be opened in append mode', $this->filename));
            }
        }

        if (!is_resource($descr)) {
            throw new \LogicException('No stream was opened yet');
        }

        // После каждой записи мы закрываем дескриптор файла для упрощения функционала
        // Также не учтена работа с конкурентным доступом к файлу, также для упрощения
        try {
            fwrite($descr, (string)$record->formatted);
        } finally {
            fclose($descr);
        }
    }

    private function createDir(string $dirname): void
    {
        if (is_dir($dirname)) {
            return;
        }

        $status = mkdir(directory: $dirname, recursive: true);

        if (false === $status && !is_dir($dirname)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirname));
        }
    }
}
