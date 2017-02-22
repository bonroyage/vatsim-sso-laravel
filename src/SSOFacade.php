<?php namespace Vatsim\OAuthLaravel;

use Illuminate\Support\Facades\Facade;

class SSOFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor () { return 'vatsimoauth'; }

}
