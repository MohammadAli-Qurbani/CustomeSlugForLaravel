<?php

namespace App\CustomeSlug;

trait CustomeSlug
{

    public static function myCustomeSlug(Object $model, String $slugAable,bool $useForUpdate=false): string
    {
         /**
             *  - first searching for white space and replace with -
             *  - second search data slug in database
             *  - thrid a record found take the last - and replace with a number plus one
             *
         */
        $slugAable = preg_replace('/\s+/', '-', trim($slugAable));
        $slugAable = trim($slugAable, '-');

        // Ensure proper handling of Farsi text by converting to UTF-8 if necessary
        $slugAable = mb_convert_encoding($slugAable, 'UTF-8', 'auto');

        $searchedSlug = $model->where('slug', 'like', "%$slugAable%")->latest('id')->first();

        if (!empty($searchedSlug)) {
            if ($useForUpdate && $slugAable === $searchedSlug->slug) {
                return $searchedSlug->slug;
            }

            $searchForSlugNumber = explode('-', $searchedSlug->slug);
            $slugNumber = (int)end($searchForSlugNumber) + 1;

            return $slugAable . '-' . $slugNumber;
        }

        return $slugAable;
    }
}

