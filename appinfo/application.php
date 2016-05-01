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

namespace OCA\VineCellar\AppInfo;

use OCP\AppFramework\App;

class Application extends App {

	public function __construct() {
		parent::__construct('vinecellar', []);
	}

}
