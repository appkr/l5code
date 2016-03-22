<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->cache = app('cache');

        if ((new \ReflectionClass($this))->implementsInterface(\App\Http\Controllers\Cacheable::class) and taggable()) {
            $this->cache = app('cache')->tags($this->cacheKeys());
        }
    }

    /**
     * Execute caching against database query.
     *
     * @see config/project.php's cache section.
     *
     * @param string $key
     * @param int $minutes
     * @param \App\Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *        |\Illuminate\Database\Eloquent\Relations\Relation $query
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     */
    protected function cache($key, $minutes, $query, $method, ...$args)
    {
        $args = (! empty($args)) ? implode(',', $args) : null;

        if (config('project.cache') === false) {
            return $query->{$method}($args);
        }

        return $this->cache->remember($key, $minutes, function() use($query, $method, $args) {
            return $query->{$method}($args);
        });
    }
}
