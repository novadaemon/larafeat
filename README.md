# novadaemon/larafeat

Larafeat is a simplified modification of the [Lucidarch](https://docs.lucidarch.dev). It allows you to build and serve features in your Laravel application.

![Larafeat](https://banners.beyondco.de/Larafeat.png?theme=light&packageManager=composer+require&packageName=novadaemon%2Flarafeat&pattern=circuitBoard&style=style_1&description=Easy+way+to+implement+DDD+pattern+in+your+Laravel+application&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

## Instalation

You can install the package via composer:

```bash
composer require novadaemon/larafeat
```

This package supports Laravel 9 and Laravel 10.

## Usage

Create a new feature using the command `make:feature`

```bash
php artisan make:feature MyAwesomeFeature
```

You can write the name without the "Feature" prefix, the command prepend it to the class and file names.

```bash
php artisan make:feature MyAwesome
```

The above command create the file **MyAwesomeFeature** class in the directory **app/Features**.

```php
<?php

namespace App\Features;

use Illuminate\Http\Request;
use Novadaemon\Larafeat\Feature;

class MyAwsomeFeature extends Feature
{
    public function handle(Request $request)
    {
        $order = $request->input('order');

        //...
    }
}
```

Also, the command generates the file **MyAwesomeFeatureTest** in the *tests* directory.

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Features\MyAwsomeFeature;

class MyAwsomeFeatureTest extends TestCase
{
    public function test_my_awsome_feature()
    {
        $this->markTestIncomplete();
    }
}
```

The `make:fature` command accept the `--pest` option. If this option is present, the file create for test will be a [Pest](https://pestphp.com) file.

```php
<?php

namespace Tests\Feature;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
```

## Usage

To start serves features, you must extends your controller from Larafeat controller.

```php
<?php

namespace App\Controllers;

use Novadaemon\Larafeat\Controllers\Controller;
use App\Features\MyAwesomeFeature;

class MyController extends Controller
{
    public function get()
    {
        return $this->serve(MyAwesomeFeature::class);
    }
}
```

You can define class properties in the Feature constructor, and then, you can pass then in the **arguments** parameter of the **serve** method.

```php
<?php

namespace App\Features;

use Illuminate\Http\Request;
use Novadaemon\Larafeat\Feature;

class MyAwsomeFeature extends Feature
{
    public function __construct(private string $name)
    {
    }

    public function handle(Request $request)
    {
        $name = $this->name;

        //...
    }
}
```

```php

<?php

namespace App\Controllers;

use Novadaemon\Larafeat\Controllers\Controller;
use App\Features\MyAwesomeFeature;

class MyController extends Controller
{
    public function get()
    {
        return $this->serve(MyAwesomeFeature::class, ['name' => 'Jesús']);
    }
}
```

Inside the feature yo can disptach [Laravel Jobs](https://laravel.com/docs/10.x/queues#creating-jobs) using the **run** method.

```php
<?php

namespace App\Features;

use App\Jobs\GreetingJob;
use Illuminate\Http\Request;
use Novadaemon\Larafeat\Feature;

class MyAwsomeFeature extends Feature
{
    public function __construct(private string $name)
    {
    }

    public function handle(Request $request)
    {
        return $this->run(new GrettingJob($this->name));
    }
}
```

## Contributing

Contributing is pretty chill and is highly appreciated! Just send a PR and/or create an issue!

## Credits

- [Jesús García](https://github.com/novadaemon)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.