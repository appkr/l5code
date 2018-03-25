<?php

interface StorageInterface
{
    public function put($item);

    public function collection();
}