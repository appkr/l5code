<?php

namespace App\Transformers;

use App\Tag;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class TagTransformer extends TransformerAbstract
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
    ];

    /**
     * Transform single resource.
     *
     * @param  \App\Tag $tag
     * @return  array
     */
    public function transform(Tag $tag)
    {
        $payload = [
            'id' => (int) $tag->id,
            'slug' => $tag->slug,
            'name_en' => $tag->en,
            'name_ko' => $tag->ko,
            'articles' => (int) $tag->articles->count(),
            'links' => [
                [
                    'rel' => 'api.v1.tags.articles.index',
                    'href' => route('api.v1.tags.articles.index', $tag->slug),
                ],
            ]
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

    /**
     * Include articles.
     *
     * @param  \App\Tag $tag
     * @param  \League\Fractal\ParamBag|null $params
     * @return  \League\Fractal\Resource\Collection
     */
    public function includeArticles(Tag $tag, ParamBag $params = null)
    {
        $transformer = new \App\Transformers\ArticleTransformer($params);

        $parsed = $transformer->getParsedParams();

        $articles = $tag->articles()
            ->limit($parsed['limit'])
            ->offset($parsed['offset'])
            ->orderBy($parsed['sort'], $parsed['order'])
            ->get();

        return $this->collection($articles, $transformer);
    }
}
