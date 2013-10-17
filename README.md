## Cells for Laravel 4 - Beta

Cells are view components for Laravel 4. They are mini-controllers with their own MVC stack, can invoke logic and render views.

### Installation

- [Cells on GitHub](https://github.com/torann/laravel-4-cells)

To get the latest version of Cells simply require it in your `composer.json` file.

~~~
"torann/cells": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Cells is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~php
'providers' => array(

    'Torann\Cells\CellsServiceProvider',

)
~~~

Cells also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~php
'aliases' => array(

    'Cells' => 'Torann\Cells\Facades\CellsFacade',

)
~~~

Create configuration file using artisan

~~~
$ php artisan config:publish torann/cells
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
@cell('demo', 'display')
~~~

- The first parameter is the cell name.
- The second parameter is the view type, by default it is "display".

Calling your cell in a view and passing `Demo Cell!` as the label

~~~php
echo Cells::get('demo', 'display', array('label' => 'Demo Cell!'));
~~~

### Caching

Cells can be cached based on minutes. Simple change the `$cache` variable in the cell controller to the number of minutes the cell should be cached.

~~~php
class CellDemo extends CellBaseController {

    /**
     * Default cache value in minutes.
     *
     * @var string
     */
    public $cache = 60;

}
~~~

By default caching is disabled in the `local` environment. To enable this change the setting `disable_cache_in_dev` to *false* in the config file. 