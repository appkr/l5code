<?php

namespace App\Transformers;

use App\Attachment;
use Appkr\Api\TransformerAbstract;

class AttachmentTransformer extends TransformerAbstract
{
    /**
     * Transform single resource.
     *
     * @param  \App\Attachment $attachment
     * @return  array
     */
    public function transform(Attachment $attachment)
    {
        $payload = [
            'id' => (int) $attachment->id,
            'filename' => $attachment->name,
            'bytes' => (int) $attachment->bytes,
            'mime' => $attachment->mime,
            'created' => $attachment->created_at->toIso8601String(),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => url(sprintf(
                        '%s/files/%s',
                        config('project.url'), $attachment->filename
                    )),
                ],
            ],
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }
}
