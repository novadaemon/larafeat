<?php

namespace Novadaemon\Larafeat\Traits;

use Illuminate\Foundation\Bus\DispatchesJobs;

trait JobDispatcher
{
    use DispatchesJobs;

    /**
     * Excute the job
     *
     * @param [type] $job
     * @return void
     */
    public function run($job)
    {
        return $this->dispatchSync($job);
    }
}
