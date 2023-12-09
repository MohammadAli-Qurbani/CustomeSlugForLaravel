<?php

namespace App\CustomeSlug;

class CustomeSlug
{

    public static function myCustomeSlug(Object $model, String $SlugAble,bool $useForUpdate=false): string
    {
        /**
             *  - first searching or white space and replace with underscore
             *  - second search data slug in database
             *  - thrid a record found take the last underscore and replace with a number plus one
             *
         */
        $SlugAble = str_replace(' ', '_', $SlugAble);

        $searchedSlug = $model->where('slug','like',"%".$SlugAble."%")->latest('id')->first();

        if (!empty($searchedSlug)) {

            if($useForUpdate && $SlugAble===$searchedSlug){
                return $searchedSlug;
            }
            $searchForSlugNumber = explode('_', $searchedSlug->slug);

            $slugNumber = (int)end($searchForSlugNumber)+1;

            return $SlugAble . '_' . $slugNumber ;

        }

        return $SlugAble;
    }
}

