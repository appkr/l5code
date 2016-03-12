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
        $index = markdown($this->docs->get());
        $content = markdown($this->docs->get($file ?: 'installation.md'));

        return view('docs.show', compact('index', 'content'));
    }
}
