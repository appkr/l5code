<?php

require 'StorageInterface.php';

class FileStorage implements StorageInterface
{
    public function put($item)
    {
        file_put_contents('storage.txt', json_encode($item).PHP_EOL, FILE_APPEND);
    }

    public function collection()
    {
        $collection = [];
        $lines = explode(PHP_EOL, file_get_contents('storage.txt'));

        foreach($lines as $line) {
            if (! $line) continue;
            $collection[] = json_decode($line);
        }

        return $collection;
    }
}