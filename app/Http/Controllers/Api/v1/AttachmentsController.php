<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\Http\Controllers\AttachmentsController as ParentController;

class AttachmentsController extends ParentController
{
    /**
     * AttachmentsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware = [];
        $this->middleware('jwt.auth', ['except' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function index(Article $article)
    {
        return json()->withCollection(
            $article->attachments,
            new \App\Transformers\AttachmentTransformer
        );
    }
}