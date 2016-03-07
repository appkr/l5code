<?php

namespace App;

use File;

class Documentation
{
    /**
     * 주어진 파일의 내용을 조회한다.
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
     * 주어진 파일의 절대 경로를 계산한다.
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
     * 링크에 포함된 불필요한 문자열을 제거한다.
     *
     * @param $content
     * @return string
     */
    protected function replaceLinks($content)
    {
        return str_replace('/docs/{{version}}', '/docs', $content);
    }
}
