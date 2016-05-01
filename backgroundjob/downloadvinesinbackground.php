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

use OC;
use OC\BackgroundJob\TimedJob;
use OCA\VineCellar\AppInfo\Application;
use OCA\VineCellar\Service\VineDownloader;
use OCP\IUserManager;

class DownloadVinesInBackground extends TimedJob {

	public function __construct() {
		$this->setInterval(12 * 60 * 60); // Twice a day
	}

	protected function run($argument) {
		$app = new Application();
		$container = $app->getContainer();

		/* @var $userManager IUserManager */
		$userManager = OC::$server->getUserManager();
		/* @var $vineDownloader VineDownloader */
		$vineDownloader = $container->query(VineDownloader::class);

		$userManager->callForAllUsers(function ($user) use ($vineDownloader) {
			$vineDownloader->downloadUsersLikeVines($user);
		});
	}

}
