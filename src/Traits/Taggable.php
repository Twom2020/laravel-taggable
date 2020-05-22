<?php


namespace Twom\Taggable\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Twom\Taggable\Models\Tag;
use Twom\Taggable\Facades\Tag as TagFacade;


/**
 * Trait Taggable
 * @package Twom\Taggable\Traits
 *
 * @method static Builder hasTag($names, $conditionType = "where")
 */
trait Taggable
{
    /**
     * @return MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'twom_taggables');
    }

    /**
     * add (attach) tags to this object
     *
     * @param $names
     * @return bool
     */
    public function tag($names)
    {
        return TagFacade::add($names, $this->tags());
    }

    /**
     * sync tags for this object
     *
     * @param $names
     * @return bool
     */
    public function stag($names)
    {
        return TagFacade::sync($names, $this->tags());
    }

    /**
     * delete (detach) tags for this object
     *
     * @param $names
     * @return bool
     */
    public function detag($names)
    {
        return TagFacade::delete($names, $this->tags());
    }

    /**
     * filter with tags
     * check has the selected tags
     *
     * @param Builder $query
     * @param $names
     * @param null $conditionType
     * @return Builder
     */
    public function scopeHasTag(Builder $query, $names, $conditionType = null)
    {
        $names = TagFacade::splitNames($names);
        $conditionType = is_null($conditionType) ? (config("taggable.filter_condition") ?? "where") : $conditionType;

        $query->whereHas('tags', function ($query) use ($names, $conditionType) {
            $condition = "where";
            foreach ($names as $name) {
                if ($conditionType == "where")
                    $query->$condition("name", $name);
                else
                    $query->$condition("name", "like", "%{$name}%");
                $condition = "orWhere";
            }
        })->with('tags');

        return $query;
    }
}
