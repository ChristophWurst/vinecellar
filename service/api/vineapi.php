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

use Closure;
use OCA\VineCellar\Service\Exception\ApiException;
use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;
use OCP\ILogger;
use Sabre\DAV\Exception;

class VineAPI {

	private $baseUrl = 'https://api.vineapp.com/';

	/** @var ILogger */
	private $logger;

	/** @var IClient */
	private $client;

	/** @var string */
	private $requestToken;

	/** @var int */
	private $userId;

	/**
	 * @param ILogger $logger
	 * @param IClientService $clientService
	 */
	public function __construct(ILogger $logger, IClientService $clientService) {
		$this->logger = $logger;
		$this->client = $clientService->newClient();
	}

	/**
	 * Log user in with the given credentials
	 *
	 * @param type $username
	 * @param type $password
	 */
	public function login($username, $password) {
		if (!is_null($this->requestToken)) {
			// Log out previous user
			$this->logger;
		}

		$this->logger->info("logging into vine as <$username>", [
			'app' => 'vinecellar'
		]);
		$response = $this->doRequest(function() use ($username, $password) {
			return $this->client->post($this->baseUrl . 'users/authenticate', [
					'body' => [
						'username' => $username,
						'password' => $password,
					],
			]);
		});
		$data = json_decode($response->getBody())->data;
		$this->requestToken = $data->key;
		$this->userId = $data->userId;
	}

	public function logout() {
		if (is_null($this->requestToken)) {
			return;
		}
		$this->doRequest(function() {
			$this->client->delete($this->baseUrl . 'users/authenticate', [
				'headers' => $this->getRequestHeaders(),
			]);
		});
	}

	public function getLikes() {
		$page = 1;
		do {
			$response = $this->doRequest(function() use ($page) {
				return $this->client->get($this->baseUrl . "timelines/users/$this->userId/likes?page=$page&size=100", [
						'headers' => $this->getRequestHeaders(),
				]);
			});
			$data = json_decode($response->getBody())->data;
			$page = (int) $data->nextPage;
			yield $data->records;
		} while (!is_null($data->nextPage) || $data->nextPage === '');
	}

	private function getRequestHeaders() {
		return [
			'vine-session-id' => $this->requestToken,
		];
	}

	/**
	 * Encapsulation of IClient request to convert \Exception to \ApiException
	 *
	 * @param Closure $c
	 * @return mixed
	 * @throws ApiException
	 */
	private function doRequest(Closure $c) {
		try {
			return $c();
		} catch (Exception $ex) {
			throw new ApiException();
		}
	}

}
