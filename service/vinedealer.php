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

use OC\Files\Node\File;
use OC\Files\Node\Folder;
use OCA\VineCellar\Service\Api\VineAPI;
use OCA\VineCellar\Service\Exception\InvalidConfigException;
use OCA\VineCellar\Service\Logger;
use OCP\Files\NotFoundException;
use OCP\IServerContainer;
use OCP\IUser;
use Piwik\Ini\IniReader;
use Piwik\Ini\IniReadingException;

class VineDealer {

	/** @var VineAPI */
	private $api;

	/** @var Sync */
	private $sync;

	/** @var Downloader */
	private $downloader;

	/** @var IniReader */
	private $iniReader;

	/** @var IServerContainer */
	private $serverContainer;

	/** @var Logger */
	private $logger;

	/**
	 * @param VineAPI $api
	 * @param Sync $sync
	 * @param Downloader $downloader
	 * @param IServerContainer $serverContainer
	 * @param IniReader $iniReader
	 * @param Logger $logger
	 */
	public function __construct(VineAPI $api, Sync $sync, Downloader $downloader, IServerContainer $serverContainer, IniReader $iniReader, Logger $logger) {
		$this->api = $api;
		$this->sync = $sync;
		$this->downloader = $downloader;
		$this->serverContainer = $serverContainer;
		$this->iniReader = $iniReader;
		$this->logger = $logger;
	}

	public function syncUsersLikedVines(IUser $user) {
		try {
			$config = $this->getConfig($user);
			$this->logger->debug("syncing liked vines for user " . $user->getUID());
			$this->api->login($config['username'], $config['password']);
			$this->syncLikes($user, $config['username']);
			$this->api->logout();
		} catch (Exception\ServiceException $ex) {
			
		}
	}

	public function downloadUsersLikedVines(IUser $user) {
		try {
			$config = $this->getConfig($user);
			$this->logger->debug("syncing liked vines for user " . $user->getUID());
			$this->downloadVines($user, 15);
		} catch (Exception\ServiceException $ex) {
			
		}
	}

	private function getConfig(IUser $user) {
		$userFolder = $this->serverContainer->getUserFolder($user->getUID());
		try {
			$configFile = $userFolder->get('/.config/vinecellar.ini');
			if ($configFile instanceof File) {
				$config = $this->iniReader->readString($configFile->getContent());
				if (is_null($config)) {
					throw new InvalidConfigException();
				}
				if (!isset($config['username']) || !isset($config['password']) || !isset($config['downloaddir'])) {
					throw new InvalidConfigException();
				}
				return $config;
			} else {
				throw new NotFoundException();
			}
		} catch (NotFoundException $ex) {
			$this->logger->debug($user->getUID() . " does not have a vine cellar config files");
			throw new Exception\ServiceException();
		} catch (IniReadingException $ex) {
			$this->logger->warning("user " . $user->getUID() . " has invalid vine cellar config");
			throw new Exception\ServiceException();
		}
	}

	private function syncLikes(IUser $user, $username) {
		foreach ($this->api->getLikes($username) as $records) {
			$this->logger->debug('syncing chunk of ' . count($records) . ' vines of user ' . $user->getUID());
			$this->sync->syncLikedVines($user, $records);
		}
	}

	private function downloadVines(IUser $user, $n) {
		$config = $this->getConfig($user);
		$folder = $this->serverContainer->getUserFolder($user->getUID());
		if (!$folder->nodeExists($config['downloaddir'])) {
			$folder->newFolder($config['downloaddir']);
		}
		$downloadFolder = $folder->get($config['downloaddir']);
		if ($downloadFolder instanceof Folder) {
			$notDownloaded = $this->downloader->downloadUsersVines($user, $downloadFolder, $n);
		} else {
			// TODO: handle error
		}
	}

}
