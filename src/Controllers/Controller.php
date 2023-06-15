<?php

namespace Novadaemon\Larafeat\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Novadaemon\Larafeat\Traits\ServesFeatures;

/**
 * Base controller.
 */
class Controller extends BaseController
{
    use ValidatesRequests;
    use ServesFeatures;
}
