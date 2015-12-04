<?php

class Competition_Module extends Core_ModuleBase
{
	/**
	 * @return Core_ModuleInfo
	 */
	protected function createModuleInfo()
	{
		return new Core_ModuleInfo(
			"Competitions",
			"Manage skating competitions",
			"Tom Davison");
	}

	public function listTabs($tabCollection)
	{
		$menu = $tabCollection->tab('competition', 'FARS', 'manager', 90);
		$menu->addSecondLevel('competition', 'Competitions', 'manager');
	}
}