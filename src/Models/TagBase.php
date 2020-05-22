<?php


namespace Twom\Taggable\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TagBase
 * @package Twom\Taggable\Models
 *
 * @method static Builder withUsedCount()
 */
abstract class TagBase extends Model
{
    public $timestamps = false;
    protected $table = "twom_tags";
    protected $fillable = [
        'name'
    ];

    /**
     * morphedByMany relation for taggables
     *
     * @param $model
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function taggable($model)
    {
        return $this->morphedByMany($model, "taggable", "twom_taggables", "tag_id");
    }

    /**
     * get tags with used_count
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithUsedCount(Builder $query)
    {
        $query->select("*")->selectSub(function ($query) {
            $query->selectRaw('count(*)')
                ->from('twom_taggables')
                ->whereColumn('taggables.tag_id', 'tags.id');
        }, 'used_count');

        return $query;
    }
}
