<?php namespace Torann\Cells\Commands;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CellsGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cells:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate cell structure';

	/**
	 * Repository config.
	 *
	 * @var Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * Filesystem
	 *
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Repository $config, File $files)
	{
		$this->config = $config;
		$this->files = $files;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		// Cell controller name is camel case.
		$cellControllerName = 'Cell'.ucfirst($this->getCellName());

		// Cell controller file is camel with php extension.
		$cellControllerFile = $cellControllerName.'.php';

		// Cell template is lower.
		$cellName  = $this->getCellName();

		// Create cell directory.
		if ( ! $this->files->isDirectory(app_path().'/cells'))
		{
			$this->files->makeDirectory(app_path().'/cells', 0777, true);
		}

		// Cell controller already exists.
		if ($this->files->exists(app_path()."/cells/$cellControllerFile"))
		{
			return $this->error('Cell "'.$this->getCellName().'" is already exists.');
		}


		// Get controller template.
		$cellControllerTemplate = $this->getTemplate('cellController');

		// Prepare controller template.
		$cellControllerTemplate = preg_replace(
			array('|\{cellClass\}|', '|\{cellName\}|'),
			array($cellControllerName, $cellName),
			$cellControllerTemplate
		);

		// Create controller file.
		$this->files->put(app_path()."/cells/$cellControllerFile", $cellControllerTemplate);


		// CREATE CELL VIEW
		// Create cell view directory.
		if ( ! $this->files->isDirectory(app_path()."/cells/$cellName"))
		{
			$this->files->makeDirectory(app_path()."/cells/$cellName", 0777, true);
		}

		// Make view file example.
		$this->files->put(app_path()."/cells/$cellName/display.blade.php", $this->getTemplate('cell.blade'));

		$this->info('Cell "'.$this->getCellName().'" has been created.');
	}

	/**
	 * Get the cell name.
	 *
	 * @return string
	 */
	protected function getCellName()
	{
		return strtolower($this->argument('name'));
	}

	/**
	 * Get default template.
	 *
	 * @param  string $template
	 * @return string
	 */
	protected function getTemplate($template)
	{
		$path = realpath(__DIR__.'/../templates/'.$template.'.txt');

		return $this->files->get($path);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	*/
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the cell to generate.')
		);
	}

}