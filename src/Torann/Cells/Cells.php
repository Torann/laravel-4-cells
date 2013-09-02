<?php namespace Torann\Cells;

use Closure;
use ReflectionClass;
use Illuminate\Http\Response;
use Illuminate\View\Environment;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Symfony\Component\HttpFoundation\Cookie;

class Cells {

	/**
	 * Environment view.
	 *
	 * @var Illuminate\View\Environment
	 */
	protected $view;

	/**
	 * Create a new instance.
	 *
	 * @param  \Illuminate\Config\Repository     $config
	 * @param  \Illuminate\View\Environment      $view
	 * @param  \Illuminate\Filesystem\Filesystem $files
	 * @return void
	 */
	public function __construct(Environment $view)
	{
		$this->view = $view;

		$this->view->addLocation(app_path()."/cells");
	}

	/**
	 * Cell instance.
	 *
	 * @param  string $className
	 * @param  array  $attributes
	 * @return Torann\Cells
	 */
	public function get($className, $attributes = array())
	{
		static $cells = array();

		// If the class name is not lead with upper case add prefix "Cell".
		if ( ! preg_match('|^[A-Z]|', $className))
		{
			$className = 'Cell'.ucfirst($className);
		}

		if ( ! $instance = array_get($cells, $className))
		{
			$reflector = new ReflectionClass($className);

			if ( ! $reflector->isInstantiable())
			{
				throw new UnknownCellClassException("Cell target [$className] is not instantiable.");
			}

			$instance = $reflector->newInstance($this->view);

			array_set($cells, $className, $instance);
		}

		$instance->setAttributes($attributes);

		$instance->beginCell();

		$instance->endCell();

		return $instance->display();
	}

}