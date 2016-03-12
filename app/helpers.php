<?php

if (! function_exists('markdown')) {
    /**
     * 마크다운 문서를 HTML로 변환한다.
     *
     * @param null $text
     * @return mixed|string
     */
    function markdown($text = null) {
        return app(ParsedownExtra::class)->text($text);
    }
}
