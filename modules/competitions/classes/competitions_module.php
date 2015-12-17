<?php

class Competitions_Module extends Core_ModuleBase
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
		$menu = $tabCollection->tab('competitions', 'FARS', 'competitions', 90);
		$menu->addSecondLevel('competitions', 'Competitions', 'competitions');
		$menu->addSecondLevel('categories', 'Categories', 'categories');
	}
}