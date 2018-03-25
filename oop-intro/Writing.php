<?php

// require 'ArrayStorage.php';

abstract class Writing
{
    protected $title;

    protected $viewCount = 0;

    protected $storage;

    public function __construct($title, StorageInterface $storage)
    {
        $this->setTitle($title);
        $this->storage = $storage;
    }

    abstract public function save();

    public function increaseViewCount()
    {
        $this->viewCount += 1;
    }

    protected function setTitle($title)
    {
        if (strlen($title) < 10) {
            throw new Exception('10 글자보다 긴 제목을 입력해 주세요.');
        }

        $this->title = $title;
    }

    public function getTitle()
    {
        return ucfirst($this->title);
    }
}