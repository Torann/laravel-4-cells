<?php namespace Torann\Cells;

use ReflectionClass;
use Illuminate\View\Factory;

class Cells {

	/**
	 * Factory view.
	 *
	 * @var Illuminate\View\Factory
	 */
	protected $view;

	/**
	 * Cache Toggle.
	 *
	 * @var Boolean
	 */
	protected $caching_disabled = false;

	/**
	 * Create a new instance.
	 *
	 * @param  \Illuminate\View\Factory      $view
	 * @return void
	 */
	public function __construct(Factory $view, $caching_disabled)
	{
		$this->view 			= $view;
		$this->caching_disabled = $caching_disabled;

		$this->view->addLocation(app_path()."/cells");
	}

	/**
	 * Cell instance.
	 *
	 * @param  string $className
	 * @param  string $action
	 * @param  array  $attributes
	 * @return Torann\Cells
	 */
	public function get($className, $action = 'display', $attributes = array())
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

			$instance = $reflector->newInstance($this->view, $this->caching_disabled);

			array_set($cells, $className, $instance);
		}

		$instance->setAttributes($attributes);

		$instance->initCell( $action );

		return $instance->displayView();
	}

}