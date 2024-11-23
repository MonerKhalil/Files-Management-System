<?php

namespace App\Helpers\ClassesStatic;

use App\Helpers\ClassesBase\Models\BaseTranslationModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdapterData
{
    /**
     * @param mixed $collection
     * @return Collection|LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @author moner khalil
     */
    public static function manyDataTranslation(mixed $collection)
    {
        if ($collection instanceof LengthAwarePaginator){
            return $collection->through(function ($item){
                return self::singleDataTranslation($item);
            });
        }
        if ($collection instanceof Collection){
            return $collection->map(function ($item){
                return self::singleDataTranslation($item);
            });
        }
        return $collection;
    }

    /**
     * @param mixed $item
     * @return mixed
     * @author moner khalil
     */
    public static function singleDataTranslation(mixed $item){
        if ($item instanceof BaseTranslationModel){
            return $item->dataTransform($item);
        }
        return $item;
    }
}
