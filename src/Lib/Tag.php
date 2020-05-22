<?php


namespace Twom\Taggable\Lib;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Tag
 * @package Twom\Taggable\Lib
 *
 * @property Builder $model
 */
class Tag
{
    protected $model = null;

    public function __construct()
    {
        $model = config("taggable.model");
        $this->model = (new $model);
    }

    /**
     * get list of tags
     *
     * @return Builder[]|Collection
     */
    public function list()
    {
        return $this->model->get();
    }

    /**
     * get list with used_count
     *
     * @return mixed
     */
    public function listWithCount()
    {
        return $this->model->withUsedCount()->get();
    }

    /**
     * do action before create or sync
     *
     * @param $names
     * @return Builder[]|Collection
     */
    protected function createRequirements($names)
    {
        $names = $this->splitNames($names);
        $this->createBatch($this->checkNotExists($names));
        return $this->getTags($names);
    }

    /**
     * add tags
     *
     * @param $names
     * @param MorphToMany $relation
     * @return bool
     */
    public function add($names, MorphToMany $relation)
    {
        $tags = $this->createRequirements($names);
        $relation->detach($tags);
        $relation->attach($tags);
        return true;
    }

    /**
     * sync tags
     *
     * @param $names
     * @param MorphToMany $relation
     * @return bool
     */
    public function sync($names, MorphToMany $relation)
    {
        $relation->sync($this->createRequirements($names));
        return true;
    }

    /**
     * detach tags
     *
     * @param $names
     * @param MorphToMany $relation
     * @return bool
     */
    public function delete($names, MorphToMany $relation)
    {
        $relation->detach($this->getTags($names));
        return true;
    }

    /**
     * check not exists
     *
     * @param array $names
     * @return array
     */
    public function checkNotExists(array $names)
    {
        $shouldCreateList = [];
        foreach ($names as $name) {
            $check = $this->model->where("name", trim($name))->first();
            if (empty($check))
                $shouldCreateList[] = $name;
        }

        return $shouldCreateList;
    }

    /**
     * create batch
     *
     * @param array $names
     * @return bool
     */
    public function createBatch(array $names)
    {
        $names = array_map(function ($item) {
            return [
                "name" => $this->nameRegex(is_array($item) ? $item['name'] : $item),
            ];
        }, $names);

        return $this->model->insert($names);
    }

    /**
     * get tags
     *
     * @param $names
     * @return Builder[]|Collection
     */
    public function getTags($names)
    {
        return $this->model->whereIn("name", $this->splitNames($names))->get();
    }

    /**
     * split names
     *
     * @param $names
     * @return array
     */
    public function splitNames($names)
    {
        if (is_string($names)) {
            if (strpos($names, ","))
                return array_map(function ($item) {return $this->nameRegex($item);}, explode(",", $names));
            else
                return [$names];
        }
        return $names;
    }

    /**
     * replace space with _
     *
     * @param $name
     * @return string|string[]|null
     */
    public function nameRegex($name)
    {
        return preg_replace('/\s+/', '_', trim($name));
    }
}
