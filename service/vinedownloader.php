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

namespace OCA\VineCellar\Service;

use OCP\IUser;

class VineDownloader {

	/** @var Logger */
	private $logger;

	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	public function downloadUsersVines(IUser $user) {
		$this->logger->debug("downloading vines for user " . $user->getUID());
	}

}
