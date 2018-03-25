<?php

namespace App\Transformers;

use App\User;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include using url query string.
     * e.g. collection case -> ?include=comments:limit(5|1):order(created_at|desc)
     *      item case       -> ?include=author
     *
     * @var  array
     */
    protected $availableIncludes = [
        'articles',
        'comments',
    ];

    /**
     * Transform single resource.
     *
     * @param  \App\User $user
     * @return  array
     */
    public function transform(User $user)
    {
        $payload = [
            'id' => optimus($user->id),
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => 'http:' . gravatar_profile_url($user->email),
            'created' => $user->created_at->toIso8601String(),
            'articles' => (int) $user->articles->count(),
            'comments' => (int) $user->comments->count(),
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

    /**
     * Include articles.
     *
     * @param  \App\User $user
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     */
    public function includeArticles(User $user, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\ArticleTransformer($params);

        $parsed = $transformer->getParsedParams();

        $articles = $user->articles()
            ->limit($parsed['limit'])
            ->offset($parsed['offset'])
            ->orderBy($parsed['sort'], $parsed['order'])
            ->get();

        return $this->collection($articles, $transformer);
    }

    /**
     * Include comments.
     *
     * @param  \App\User $user
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     */
    public function includeComments(User $user, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\CommentTransformer($params);

        $parsed = $transformer->getParsedParams();

        $comments = $user->comments()
            ->limit($parsed['limit'])
            ->offset($parsed['offset'])
            ->orderBy($parsed['sort'], $parsed['order'])
            ->get();

        return $this->collection($comments, $transformer);
    }
}
