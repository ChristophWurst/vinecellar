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

namespace OCA\VineCellar\Controller;

use OCA\VineCellar\Service\VineDealer;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IUserManager;

class DownloadController extends Controller {

	/** @var VineDealer */
	private $dealer;

	/** @var IUserManager */
	private $userManager;

	/** @var string */
	private $userId;

	/**
	 * @param type $appName
	 * @param IRequest $request
	 * @param string $UserId
	 * @param VineDealer $dealer
	 */
	public function __construct($appName, IRequest $request, IUserManager $userManager, $UserId, VineDealer $dealer) {
		parent::__construct($appName, $request);
		$this->userManager = $userManager;
		$this->userId = $UserId;
		$this->dealer = $dealer;
	}

	/**
	 * @NoCSRFRequired
	 * @NoAdminRequired
	 */
	public function index() {
		$user = $this->userManager->get($this->userId);
		return $this->dealer->downloadUsersLikedVines($user);
	}

}
