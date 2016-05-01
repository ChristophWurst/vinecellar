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

use OC\Files\Node\Folder;
use OCA\VineCellar\Db\Vine;
use OCA\VineCellar\Db\VineMapper;
use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;
use OCP\IUser;
use Sabre\DAV\Exception;

class Downloader {

	/** @var VineMapper */
	private $mapper;

	/** @var IClient */
	private $client;

	public function __construct(VineMapper $mapper, IClientService $clientService) {
		$this->mapper = $mapper;
		$this->client = $clientService->newClient();
	}

	public function downloadUsersVines(IUser $user, Folder $folder, $count) {
		$vines = $this->mapper->findNotDownloadedVines($user->getUID(), $count);
		foreach ($vines as $vine) {
			/* @var $vines Vine */
			$downloadUrl = $vine->getVideoUrl();
			try {
				$response = $this->client->get($downloadUrl);
				$data = $response->getBody();
				$file = $folder->newFile($vine->getId() . '.mp4');
				$file->putContent($data);

				$vine->setDownloaded((int) true);
				$this->mapper->update($vine);
			} catch (Exception $ex) {
				
			}
		}
	}

}
