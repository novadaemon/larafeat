<?php

namespace Novadaemon\Larafeat\Traits;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Novadaemon\Larafeat\Feature;

trait ServesFeatures
{
    use DispatchesJobs;
    use Marshal;

    public function serve(Feature|string $feature, array $arguments = [])
    {
        $feature = is_object($feature) ? $feature : $this->marshal($feature, new Collection(), $arguments);

        return $this->dispatchSync($feature);
    }
}
