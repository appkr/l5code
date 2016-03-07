<?php

namespace App;

use File;

class Documentation
{
    /**
     * Read content of given markdown file.
     *
     * @param string $file
     * @return string
     */
    public function get($file = 'documentation.md')
    {
        $content = File::get($this->path($file));

        return $this->replaceLinks($content);
    }

    /**
     * Create intervention image instance from the given file.
     *
     * @param $file
     * @return \Intervention\Image\Image
     */
    public function image($file)
    {
        return \Image::make($this->path($file, 'docs/images'));
    }

    /**
     * Calculate etag value.
     *
     * @param $file
     * @return string
     */
    public function etag($file)
    {
        $lastModified = File::lastModified($this->path($file, 'docs/images'));

        return md5($file . $lastModified);
    }

    /**
     * Generate path of the given file.
     *
     * @param string $file
     * @param string $dir
     * @return string
     */
    protected function path($file, $dir = 'docs')
    {
        $file = ends_with($file, ['.md', '.png']) ? $file : $file . '.md';
        $path = base_path($dir . DIRECTORY_SEPARATOR . $file);

        if (! File::exists($path)) {
            abort(404, '요청하신 파일이 없습니다.');
        }

        return $path;
    }

    /**
     * Replace unnecessary string in link.
     *
     * @param $content
     * @return string
     */
    protected function replaceLinks($content)
    {
        return str_replace('/docs/{{version}}', '/docs', $content);
    }
}
