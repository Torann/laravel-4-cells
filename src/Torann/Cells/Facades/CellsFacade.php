<?php namespace Torann\Cells\Facades;

use Illuminate\Support\Facades\Facade;

class CellsFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'cells'; }

}