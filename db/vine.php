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

namespace OCA\VineCellar\Db;

use OCP\AppFramework\Db\Entity;

class Vine extends Entity {

	protected $userId;
	protected $description;
	protected $permalink;
	protected $vineUserId;
	protected $username;
	protected $videoUrl;
	protected $downloaded;

}
