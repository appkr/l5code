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
        if (! File::exists($this->path($file))) {
            abort(404, '요청하신 파일이 없습니다.');
        }

        $content = File::get($this->path($file));

        return $this->replaceLinks($content);
    }

    /**
     * Generate path of the given file.
     *
     * @param $file
     * @return string
     */
    protected function path($file)
    {
        $file = ends_with($file, '.md') ? $file : $file . '.md';

        return base_path('docs' . DIRECTORY_SEPARATOR . $file);
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
