<?php

require 'Writing.php';

class Article extends Writing
{
    public function getTitle()
    {
        return str_replace(' ', '_', $this->title);
    }

    public function save()
    {
        echo '아티클을 저장합니다';
    }
}

//echo (new Article('Lorem ipsum dolor sit amet'))->getTitle();
(new Article('Lorem ipsum dolor sit amet'))->save();