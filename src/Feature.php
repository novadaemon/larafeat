<?php

namespace Novadaemon\Larafeat;

use Illuminate\Queue\SerializesModels;
use Novadaemon\Larafeat\Traits\JobDispatcher;

abstract class Feature
{
    use JobDispatcher;
    use SerializesModels;
}
