<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\AttachmentsController as ParentController;

class AttachmentsController extends ParentController
{
    /**
     * AttachmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Article $article)
    {
//        return $article->attachments()->toJson(JSON_PRETTY_PRINT);
        return json()->withCollection(
            $article->attachments,
            new \App\Transformers\AttachmentTransformer
        );
    }
}
