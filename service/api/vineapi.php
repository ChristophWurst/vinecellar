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

namespace OCA\VineCellar\Service\Api;

use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;
use OCP\ILogger;

class VineAPI {

	private $baseUrl = 'https://api.vineapp.com/';

	/** @var ILogger */
	private $logger;

	/** @var IClient */
	private $client;

	/** @var string */
	private $requestToken;

	public function __construct(ILogger $logger, IClientService $clientService) {
		$this->logger = $logger;
		$this->client = $clientService->newClient();
	}

	public function login($username, $password) {
		$this->logger->info("logging into vine as <$username>", [
			'app' => 'vinecellar'
		]);
		$response = $this->client->post($this->baseUrl . 'users/authenticate', [
			'body' => [
				'username' => $username,
				'password' => $password,
			],
		]);
		$data = json_decode($response->getBody())->data;
		$this->requestToken = $data->key;
		error_log($this->requestToken);
	}

	public function logout() {
		if (is_null($this->requestToken)) {
			return;
		}
		// TODO: log out
	}

}
