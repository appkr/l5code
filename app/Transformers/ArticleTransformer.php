<?php

namespace App\Transformers;

use App\Article;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ArticleTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = [
        'comments',
        'author',
        'tags',
        'attachments',
    ];

    /**
     * Transform single resource.
     *
     * @param  \App\Article $article
     * @return  array
     */
    public function transform(Article $article)
    {
        $obfuscatedId = optimus($article->id);

        $payload = [
            'id' => $obfuscatedId,
            'title' => $article->title,
            'content' => $article->content,
            'content_html' => markdown($article->content),
            'author' => [
                'name' => $article->user->name,
                'email'  => $article->user->email,
                'avatar' => 'http:' . gravatar_profile_url($article->user->email),
            ],
            'tags' => $article->tags->pluck('slug'),
            'view_count' => (int) $article->view_count,
            'created' => $article->created_at->toIso8601String(),
            'attachments'  => (int) $article->attachments->count(),
            'comments' => (int) $article->comments->count(),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('api.v1.articles.show', $obfuscatedId),
                ],
                [
                    'rel' => 'api.v1.articles.attachments.index',
                    'href' => route('api.v1.articles.attachments.index', $obfuscatedId),
                ],
                [
                    'rel' => 'api.v1.articles.comments.index',
                    'href' => route('api.v1.articles.comments.index', $obfuscatedId),
                ],
            ],
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

    /**
     * Include comments.
     *
     * @param  \App\Article $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     */
    public function includeComments(Article $article, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\CommentTransformer($params);

        $parsed = $transformer->getParsedParams();

        $comments = $article->comments()
                            ->limit($parsed['limit'])
                            ->offset($parsed['offset'])
                            ->orderBy($parsed['sort'], $parsed['order'])
                            ->get();

        return $this->collection($comments, $transformer);
    }

    /**
     * Include author.
     *
     * @param  \App\Article $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Item
     */
    public function includeAuthor(Article $article, ParamBag $params = null)
    {
        return $this->item(
            $article->user,
            new \App\Transformers\UserTransformer($params)
        );
    }

    /**
     * Include tags.
     *
     * @param  \App\Article $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     */
    public function includeTags(Article $article, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\TagTransformer($params);

        $parsed = $transformer->getParsedParams();

        $tags = $article->tags()
                        ->limit($parsed['limit'])
                        ->offset($parsed['offset'])
                        ->orderBy($parsed['sort'], $parsed['order'])
                        ->get();

        return $this->collection($tags, $transformer);
    }

    /**
     * Include attachments.
     *
     * @param  \App\Article $article
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     */
    public function includeAttachments(Article $article, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\AttachmentTransformer($params);

        $parsed = $transformer->getParsedParams();

        $attachments = $article->attachments()
                               ->limit($parsed['limit'])
                               ->offset($parsed['offset'])
                               ->orderBy($parsed['sort'], $parsed['order'])
                               ->get();

        return $this->collection($attachments, $transformer);
    }
}
