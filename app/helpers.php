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

if (! function_exists('gravatar_profile_url')) {
    /**
     * Generate gravatar profile page url
     *
     * @param  string $email
     * @return string
     */
    function gravatar_profile_url($email)
    {
        return sprintf("//www.gravatar.com/%s", md5($email));
    }
}

if (! function_exists('gravatar_url')) {
    /**
     * Generate gravatar image url
     *
     * @param  string  $email
     * @param  integer $size
     * @return string
     */
    function gravatar_url($email, $size = 48)
    {
        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
    }
}
