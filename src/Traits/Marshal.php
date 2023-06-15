<?php

namespace Novadaemon\Larafeat\Traits;

use ArrayAccess;
use Exception;
use ReflectionParameter;

trait Marshal
{
    /**
     * Marshal a command from the given array accessible object.
     *
     * @param  string  $command
     * @return mixed
     */
    protected function marshal($command, ArrayAccess $source, array $extras = [])
    {
        $parameters = [];

        foreach ($source as $name => $parameter) {
            $parameters[$name] = $parameter;
        }

        $parameters = array_merge($parameters, $extras);

        return app($command, $parameters);
    }

    /**
     * Get a parameter value for a marshaled command.
     *
     * @param  string  $command
     * @return mixed
     *
     * @throws Exception
     */
    protected function getParameterValueForCommand($command, ArrayAccess $source,
        ReflectionParameter $parameter, array $extras = [])
    {
        if (array_key_exists($parameter->name, $extras)) {
            return $extras[$parameter->name];
        }

        if (isset($source[$parameter->name])) {
            return $source[$parameter->name];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new Exception("Unable to map parameter [{$parameter->name}] to command [{$command}]");
    }
}
