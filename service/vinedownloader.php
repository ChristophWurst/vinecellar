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
use OCA\VineCellar\Service\Api\VineAPI;
use OCA\VineCellar\Service\Exception\InvalidConfigException;
use OCA\VineCellar\Service\Logger;
use OCP\Files\NotFoundException;
use OCP\IServerContainer;
use OCP\IUser;
use Piwik\Ini\IniReader;
use Piwik\Ini\IniReadingException;

class VineDownloader {

	/** @var VineAPI */
	private $api;

	/** @var IniReader */
	private $iniReader;

	/** @var IServerContainer */
	private $serverContainer;

	/** @var Logger */
	private $logger;

	public function __construct(VineAPI $api, IServerContainer $serverContainer, IniReader $iniReader, Logger $logger) {
		$this->api = $api;
		$this->serverContainer = $serverContainer;
		$this->iniReader = $iniReader;
		$this->logger = $logger;
	}

	private function getLoginCredentials($config) {
		if (is_null($config)) {
			throw new InvalidConfigException();
		}
		if (!isset($config['username']) || !isset($config['password'])) {
			throw new InvalidConfigException();
		}
		return [
			'username' => $config['username'],
			'password' => $config['password'],
		];
	}

	public function downloadUsersVines(IUser $user) {
		$this->logger->debug("downloading vines for user " . $user->getUID());
		$userFolder = $this->serverContainer->getUserFolder($user->getUID());
		try {
			$configFile = $userFolder->get('/.config/vinecellar.ini');
			if ($configFile instanceof File) {
				$config = $this->iniReader->readString($configFile->getContent());
				$cred = $this->getLoginCredentials($config);
				$this->api->login($cred['username'], $cred['password']);
				$likes = $this->api->getLikes($cred['username']);
				$this->api->logout();
				return $likes;
			}
		} catch (NotFoundException $ex) {
			$this->logger->debug($user->getUID() . " does not have a vine cellar config files");
		} catch (IniReadingException $ex) {
			$this->logger->warning("user " . $user->getUID() . " has invalid vine cellar config");
		}
	}

}
