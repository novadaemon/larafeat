<?php

namespace Novadaemon\Larafeat\Helpers;

use Illuminate\Support\Str as LaravelStr;

if (! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class Str
{
    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        return LaravelStr::snake($value, $delimiter);
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        return LaravelStr::studly($value);
    }

   /**
    * Determine the real name of the given name,
    * excluding the given pattern.
    * 	i.e. the name: "CreateArticleFeature.php" with pattern '/Feature.php'
    * 		will result in "Create Article".
    *
    * @param  string  $name
    * @param  string  $pattern
    * @return string
    */
   public static function realName($name, $pattern = '//')
   {
       $name = preg_replace($pattern, '', $name);

       return implode(' ', preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY));
   }

    /**
     * Get the given name formatted as a feature.
     *
     * 	i.e. "Create Post Feature", "CreatePostFeature.php", "createPost", "createe"
     * 	and many other forms will be transformed to "CreatePostFeature" which is
     * 	the standard feature class name.
     *
     * @param  string  $name
     * @return string
     */
    public static function feature($name)
    {
        $parts = array_map(function ($part) {
            return self::studly($part);
        }, explode('/', $name));
        $feature = self::studly(preg_replace('/Feature(\.php)?$/', '', array_pop($parts)).'Feature');

        $parts[] = $feature;

        return implode(DS, $parts);
    }
}
