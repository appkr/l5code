<?php

namespace App\Transformers;

use App\Comment;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class CommentTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = [
        'author',
    ];

    /**
     * Transform single resource.
     *
     * @param  \App\Comment $comment
     * @return  array
     */
    public function transform(Comment $comment)
    {
        $obfuscatedId = optimus($comment->id);

        $payload = [
            'id' => $obfuscatedId,
            'content' => $comment->content,
            'content_html' => markdown($comment->content),
            'author'       => [
                'name'   => $comment->user->name,
                'email'  => $comment->user->email,
                'avatar' => 'http:' . gravatar_profile_url($comment->user->email),
            ],
            'created' => $comment->created_at->toIso8601String(),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('api.v1.comments.show', $obfuscatedId),
                ],
            ],
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

    /**
     * Include author.
     *
     * @param  \App\Comment $comment
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Item
     */
    public function includeAuthor(Comment $comment, ParamBag $params = null)
    {
        return $this->item(
            $comment->user,
            new \App\Transformers\UserTransformer($params)
        );
    }
}
