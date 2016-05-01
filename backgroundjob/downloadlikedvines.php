<?php

/**
 * ownCloud - Vine Cellar
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @copyright Christoph Wurst 2016
 */

namespace OCA\VineCellar\BackgroundJob;

use OC\BackgroundJob\TimedJob;
use OCA\VineCellar\AppInfo\Application;
use OCA\VineCellar\Service\VineDealer;

class DownloadLikedVines extends TimedJob {

	public function __construct() {
		$this->setInterval(60 * 30); // Every 30 mins
	}

	protected function run($argument) {
		$app = new Application();
		$container = $app->getContainer();

		/* @var $vineDownloader VineDealer */
		$vineDownloader = $container->query(VineDealer::class);

		$userManager = $container->getServer()->getUserManager();
		$userManager->callForAllUsers(function ($user) use ($vineDownloader) {
			$vineDownloader->downloadUsersLikedVines($user);
		});
	}

}
