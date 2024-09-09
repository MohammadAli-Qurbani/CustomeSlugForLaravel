<?php
namespace App\Services\Slug;
class CustomeSlug{
    public  function myCustomeSlug(Object $model,string $column, string $slugAable,bool $useForUpdate=false): string
    {
        /**
             *  - first searching for white space and replace with -
             *  - second search data slug in database
             *  - thrid a record found take the last - and replace with a number plus one
             *
         */
        $trimedSlugAable = preg_replace('/\s+/', '-', trim($slugAable));
        $trimedSlugAable = trim($trimedSlugAable, '-');

        // Ensure proper handling of Farsi text by converting to UTF-8 if necessary
        $trimedSlugAable = mb_convert_encoding($trimedSlugAable, 'UTF-8', 'auto');

        $searchedSlug = $model->where($column,"ILIKE","%$slugAable%")->orderBy('id','desc')->first();
  
        if (!empty($searchedSlug)) {
            if ($useForUpdate && $trimedSlugAable === $searchedSlug->slug) {
                return $searchedSlug->slug;
            }

            $searchForSlugNumber = explode('-', $searchedSlug->slug);
            $slugNumber = (int)end($searchForSlugNumber) + 1;
           
            return $trimedSlugAable . '-' . $slugNumber;
        }

        return $trimedSlugAable;
    }
}