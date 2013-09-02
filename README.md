## Cells for Laravel 4

Cells are view components for Laravel 4. They are mini-controllers with their own MVC stack, can invoke logic and render views.

### Installation

- [Cells on GitHub](https://github.com/torann/laravel-4-cells)

To get the lastest version of Cells simply require it in your `composer.json` file.

~~~
"torann/cells": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Cells is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~
'providers' => array(

    'Torann\Cells\CellsServiceProvider',

)
~~~

Cells also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~
'aliases' => array(

    'Cells' => 'Torann\Cells\Facades\Cells',

)
~~~

### Creating a cell

You can create a cell using artisan command:

~~~
php artisan cells:create demo
~~~

> The parameter is the cell name.

Now you will see a controller class at `/app/cells/CellDemo.php` and view at `/app/cells/demo/display.blade.php`

### Rendering

Using the Blade helper

~~~html
@cell('demo')
~~~

Calling your cell in a view and passing `Demo Cell!` as the label

~~~php
echo Cells::get('demo', array('label' => 'Demo Cell!'));
~~~

### Caching

Cells can be cached based on minutes. Simple change the `$cache` varaible in the cell controller to the number of minutes the cell should be cached.

~~~php
class CellDemo extends CellBaseController {

    /**
     * Default cache value in minutes.
     *
     * @var string
     */
    public $cache = 100;

}
~~~