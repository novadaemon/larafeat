<?php

namespace Novadaemon\Larafeat\Traits;

use Exception;
use Novadaemon\Larafeat\Entities\Feature;
use Novadaemon\Larafeat\Helpers\Str;
use Symfony\Component\Finder\Finder as SymfonyFinder;

if (! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

trait Finder
{
    public function fuzzyFind($query)
    {
        $finder = new SymfonyFinder();

        $files = $finder->in($this->findServicesRootPath().'/*/Features') // features
            ->name('*.php')
            ->files();

        $matches = [
            'features' => [],
        ];

        foreach ($files as $file) {
            $base = $file->getBaseName();
            $name = str_replace(['.php', ' '], '', $base);

            $query = str_replace(' ', '', trim($query));

            similar_text($query, mb_strtolower($name), $percent);

            if ($percent > 35) {
                if (strpos($base, 'Feature.php')) {
                    $matches['features'][] = [$this->findFeature($name)->toArray(), $percent];
                }
            }
        }

        // sort the results by their similarity percentage
        $this->sortFuzzyResults($matches['features']);

        $matches['features'] = $this->mapFuzzyResults($matches['features']);

        return $matches;
    }

    /**
     * Sort the fuzzy-find results.
     *
     * @param  array  &$results
     * @return bool
     */
    private function sortFuzzyResults(&$results)
    {
        return usort($results, function ($resultLeft, $resultRight) {
            return $resultLeft[1] < $resultRight[1];
        });
    }

     /**
      * Map the fuzzy-find results into the data
      * that should be returned.
      *
      * @param  array  $results
      * @return array
      */
     private function mapFuzzyResults($results)
     {
         return array_map(function ($result) {
             return $result[0];
         }, $results);
     }

    /**
     * Find the namespace of a unit.
     *
     * @return string
     */
    public function findUnitNamespace()
    {
        return 'Novadaemon\Larafeat';
    }

    /**
     * Get the relative version of the given real path.
     *
     * @param  string  $path
     * @param  string  $needle
     * @return string
     */
    protected function relativeFromReal($path, $needle = '')
    {
        if (! $needle) {
            $needle = $this->getSourceDirectoryName().DS;
        }

        return strstr($path, $needle);
    }

    /**
     * Get the source directory name.
     *
     * @return string
     */
    public function getSourceDirectoryName()
    {
        return 'app';
    }

    /**
     * Get the namespace used for the application.
     *
     * @return string
     *
     * @throws Exception
     */
    public function findNamespace(string $dir)
    {
        // read composer.json file contents to determine the namespace
        $composer = json_decode(file_get_contents(base_path().DS.'composer.json'), true);

        // see which one refers to the "src/" directory
        foreach ($composer['autoload']['psr-4'] as $namespace => $directory) {
            $directory = str_replace(['/', '\\'], DS, $directory);
            if ($directory === $dir.DS) {
                return trim($namespace, '\\');
            }
        }

        throw new Exception('App namespace not set in composer.json');
    }

    public function findRootNamespace()
    {
        return $this->findNamespace($this->getSourceDirectoryName());
    }

    public function findAppNamespace()
    {
        return $this->findNamespace('app');
    }

    /**
     * get the root of the source directory.
     *
     * @return string
     */
    public function findSourceRoot()
    {
        return app_path();
    }

    /**
     * Find the features root path in the given service.
     *
     * @return string
     */
    public function findFeaturesRootPath()
    {
        return $this->findSourceRoot().DS.'Features';
    }

    /**
     * Find the file path for the given feature.
     *
     * @param  string  $service
     * @param  string  $feature
     * @return string
     */
    public function findFeaturePath($feature)
    {
        return $this->findFeaturesRootPath().DS."$feature.php";
    }

    /**
     * Find the test file path for the given feature.
     *
     * @param  string  $feature
     * @return string
     */
    public function findFeatureTestPath($test)
    {
        $root = $this->findFeatureTestsRootPath();

        return implode(DS, [$root, "$test.php"]);
    }

    /**
     * Find the namespace for features in the given service.
     *
     * @return string
     *
     * @throws Exception
     */
    public function findFeatureNamespace($feature)
    {
        $dirs = implode('\\', explode(DS, dirname($feature)));

        $base = $this->findRootNamespace().'\\Features';

        // greater than 1 because when there aren't subdirectories it will be "."
        if (strlen($dirs) > 1) {
            return $base.'\\'.$dirs;
        }

        return $base;
    }

    /**
     * Find the namespace for features tests in the given service.
     *
     * @return string
     */
    public function findFeatureTestNamespace()
    {
        $namespace = $this->findFeatureTestsRootNamespace();

        return $namespace;
    }

    /**
     * Find the feature for the given feature name.
     *
     * @param  string  $name
     * @return Feature
     */
    public function findFeature($name)
    {
        $name = Str::feature($name);
        $fileName = "$name.php";

        $finder = new SymfonyFinder();
        $files = $finder->name($fileName)->in($this->findServicesRootPath())->files();
        foreach ($files as $file) {
            $path = $file->getRealPath();
            $serviceName = strstr($file->getRelativePath(), DS, true);
            $service = $this->findService($serviceName);
            $content = file_get_contents($path);

            return new Feature(
                Str::realName($name, '/Feature/'),
                $fileName,
                $path,
                $this->relativeFromReal($path),
                $service,
                $content
            );
        }
    }

    /**
     * Get the root path to feature tests directory.
     *
     * @return string
     */
    protected function findFeatureTestsRootPath()
    {
        return base_path().DS.'tests'.DS.'Feature';
    }

    protected function findFeatureTestsRootNamespace()
    {
        return 'Tests\\Feature';
    }
}
