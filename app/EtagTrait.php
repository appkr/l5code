<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait EtagTrait
{
    /**
     * Generate Etag value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param null $cacheKey
     * @return string
     */
    public function etag(Model $model, $cacheKey = null)
    {
        $etag = '';

        if ($model->usesTimestamps()) {
            $etag .= $model->updated_at->timestamp;
        }

        return md5($etag.$cacheKey);
    }

    /**
     * Create etag against collection of resources.
     *
     * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator $collection
     * @param string|null $cacheKey
     * @return string
     */
    protected function etags($collection, $cacheKey = null)
    {
        $etag = '';

        foreach($collection as $instance) {
            $etag .= $this->etag($instance);
        }

        return md5($etag.$cacheKey);
    }
}