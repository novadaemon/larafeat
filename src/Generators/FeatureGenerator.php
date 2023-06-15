<?php

namespace Novadaemon\Larafeat\Generators;

use Exception;
use Novadaemon\Larafeat\Entities\Feature;
use Novadaemon\Larafeat\Helpers\Str;

if (! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class FeatureGenerator extends Generator
{
    public function generate(string $feature, bool $pest)
    {
        $feature = Str::feature($feature);

        $path = $this->findFeaturePath($feature);
        $classname = $this->classname($feature);

        if ($this->exists($path)) {
            throw new Exception('Feature already exists!');

            return false;
        }

        $namespace = $this->findFeatureNamespace($feature);

        $content = file_get_contents($this->getStub());

        $content = str_replace(
            ['{{feature}}', '{{namespace}}', '{{unit_namespace}}'],
            [$classname, $namespace, $this->findUnitNamespace()],
            $content
        );

        $this->createFile($path, $content);

        // generate test file
        $this->generateTestFile($feature, $pest);

        return new Feature(
            $feature,
            basename($path),
            $path,
            $this->relativeFromReal($path),
            $content
        );
    }

    private function classname($feature)
    {
        $parts = explode(DS, $feature);

        return array_pop($parts);
    }

    /**
     * Generate the test file.
     */
    private function generateTestFile(string $feature, bool $pest)
    {
        $content = file_get_contents($pest ? $this->getPestTestStub() : $this->getTestStub());

        $namespace = $this->findFeatureTestNamespace();
        $featureClass = $this->classname($feature);
        $featureNamespace = $this->findFeatureNamespace($feature).'\\'.$featureClass;
        $testClass = $featureClass.'Test';

        $content = str_replace(
            ['{{namespace}}', '{{testclass}}', '{{feature}}', '{{feature_namespace}}'],
            [$namespace, $testClass, Str::snake(str_replace(DS, '', $feature)), $featureNamespace],
            $content
        );

        $path = $this->findFeatureTestPath($feature.'Test');

        $this->createFile($path, $content);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/feature.stub';
    }

    /**
     * Get the test stub file for the generator.
     *
     * @return string
     */
    private function getTestStub()
    {
        return __DIR__.'/stubs/feature-test.stub';
    }

    /**
     * Get the pest test stub file for the generator.
     *
     * @return string
     */
    private function getPestTestStub()
    {
        return __DIR__.'/stubs/feature-pest-test.stub';
    }
}
