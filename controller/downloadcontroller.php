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

use OCA\VineCellar\Service\VineDownloader;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IUserManager;

class DownloadController extends Controller {

	/** @var VineDownloader */
	private $downloader;

	/** @var IUserManager */
	private $userManager;

	/** @var string */
	private $user;

	/**
	 * @param type $appName
	 * @param IRequest $request
	 * @param string $UserId
	 * @param VineDownloader $downloader
	 */
	public function __construct($appName, IRequest $request, IUserManager $userManager, $UserId, VineDownloader $downloader) {
		parent::__construct($appName, $request);
		$this->userManager = $userManager;
		$this->user = $UserId;
		$this->downloader = $downloader;
	}

	/**
	 * @NoCSRFRequired
	 * @NoAdminRequired
	 */
	public function index() {
		$user = $this->userManager->get($this->user);
		return $this->downloader->downloadUsersVines($user);
	}

}
