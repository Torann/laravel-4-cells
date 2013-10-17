<?php namespace Torann\Cells;

use Illuminate\Support\ClassLoader;
use Illuminate\Support\ServiceProvider;

class CellsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Autoload for cell factory.
		ClassLoader::addDirectories(array(
			app_path().'/cells'
		));

		$this->package('torann/cells');

		// Temp to use in closure.
		$app = $this->app;
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register providers.
		$this->registerCells();

		// Register commands.
		$this->registerCellsGenerator();

		//extend blade engine by adding @cell compile function
		$this->app['view.engine.resolver']->resolve('blade')->getCompiler()->extend(function($view)
		{
			$html = "$1<?php echo Cells::get$2; ?>";
			return preg_replace("/(?<!\w)(\s*)@cell(\s*\(.*\))/", $html, $view);
		});

		// Assign commands.
		$this->commands(
			'cells.create'
		);
	}

	/**
	 * Register cell provider.
	 *
	 * @return void
	 */
	public function registerCells()
	{
		$this->app['cells'] = $this->app->share(function($app)
		{
			$caching_disabled = $app->environment() === 'local' && $app['config']['cells::disable_cache_in_dev'];
			return new Cells($app['view'], $caching_disabled);
		});
	}

	/**
	 * Register generator of cell.
	 *
	 * @return void
	 */
	public function registerCellsGenerator()
	{
		$this->app['cells.create'] = $this->app->share(function($app)
		{
			return new Commands\CellsGeneratorCommand($app['config'], $app['files']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cells');
	}

}