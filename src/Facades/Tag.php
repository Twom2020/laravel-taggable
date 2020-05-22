<?php


namespace Twom\Taggable\Facades;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Facade;

/**
 * Class Tag
 * @package Twom\Taggable\Facades
 *
 * @method static Builder[]|Collection list()
 * @method static Builder[]|Collection listWithCount()
 * @method static bool add($names, MorphToMany $relation)
 * @method static bool sync($names, MorphToMany $relation)
 * @method static bool delete($names, MorphToMany $relation)
 * @method static array checkNotExists(array $names)
 * @method static bool createBatch(array $names)
 * @method static Builder[]|Collection getTags($names)
 * @method static array splitNames($names)
 * @method static string|string[]|null nameRegex($name)
 */
class Tag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Twom\Taggable\Lib\Tag::class;
    }
}
