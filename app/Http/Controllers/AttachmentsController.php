<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    /**
     * AttachmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attachments = [];

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach($files as $file) {
                $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
                $file->move(attachments_path(), $filename);
                $payload = [
                    'filename' => $filename,
                    'bytes' => $file->getClientSize(),
                    'mime' => $file->getClientMimeType()
                ];

                $attachments[] = ($id = $request->input('article_id'))
                    ? \App\Article::findOrFail($id)->attachments()->create($payload)
                    : \App\Attachment::create($payload);
            }
        }

        return response()->json($attachments, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Attachment $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Attachment $attachment)
    {
        $path = attachments_path($attachment->name);

        if (\File::exists($path)) {
            \File::delete($path);
        }

        $attachment->delete();

        return response()->json($attachment, 200, [], JSON_PRETTY_PRINT);
    }
}
