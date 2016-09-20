<?php

if (! function_exists('markdown')) {
    /**
     * Compile Markdown to HTML.
     *
     * @param string|null $text
     * @return string
     */
    function markdown($text = null) {
        return app(ParsedownExtra::class)->text($text);
    }
}

if (! function_exists('gravatar_profile_url')) {
    /**
     * Generate gravatar profile page url.
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
     * Generate gravatar image url.
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

if (! function_exists('attachments_path')) {
    /**
     * Generate attachments path.
     *
     * @param string $path
     * @return string
     */
    function attachments_path($path = null)
    {
        return public_path('files'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if (! function_exists('format_filesize')) {
    /**
     * Calculate human-readable file size string.
     *
     * @param $bytes
     * @return string
     */
    function format_filesize($bytes)
    {
        if (! is_numeric($bytes)) return 'NaN';

        $decr = 1024;
        $step = 0;
        $suffix = ['bytes', 'KB', 'MB'];

        while (($bytes / $decr) > 0.9) {
            $bytes = $bytes / $decr;
            $step ++;
        }

        return round($bytes, 2) . $suffix[$step];
    }
}
