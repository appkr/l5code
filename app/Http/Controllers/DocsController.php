<?php

namespace App\Http\Controllers;

class DocsController extends Controller
{
    /**
     * @var \App\Documentation
     */
    protected $docs;

    /**
     * DocsController.
     *
     * @param \App\Documentation $docs
     */
    public function __construct(\App\Documentation $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Display the specified resource.
     *
     * @param  string|null $file
     * @return \Illuminate\Http\Response
     */
    public function show($file = null)
    {
        $index = \Cache::remember('docs.index', 120, function () {
            return markdown($this->docs->get());
        });

        $content = \Cache::remember("docs.{$file}", 120, function () use ($file) {
            return markdown($this->docs->get($file ?: 'installation.md'));
        });

        return view('docs.show', compact('index', 'content'));
    }

    /**
     * Respond the requested image.
     *
     * @param $file
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function image($file)
    {
        $reqEtag = \Request::getEtags();
        $genEtag = $this->docs->etag($file);

        if (isset($reqEtag[0])) {
            if ($reqEtag[0] === $genEtag) {
                return response('', 304);
            }
        }

        $image = $this->docs->image($file);

        return response($image->encode('png'), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=0',
            'Etag' => $genEtag,
        ]);
    }
}
