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

use OCA\VineCellar\Db\Vine;
use OCA\VineCellar\Db\VineMapper;
use OCP\IUser;

class Sync {

	/** @var VineMapper */
	private $mapper;

	/** @var Logger */
	private $logger;

	/**
	 * @param VineMapper $mapper
	 * @param \OCA\VineCellar\Service\Logger $logger
	 */
	public function __construct(VineMapper $mapper, Logger $logger) {
		$this->mapper = $mapper;
		$this->logger = $logger;
	}

	/**
	 * @param IUser $user
	 * @param array $records
	 */
	public function syncLikedVines(IUser $user, array $records) {
		$ids = array_map(function ($record) {
			return (int) $record->postId;
		}, $records);

		$existing = $this->mapper->findByIds($user->getUID(), $ids);
		$existingIds = array_map(function ($vine) {
			return (int) $vine->getId();
		}, $existing);

		$newRecords = array_filter($records, function ($record) use ($existingIds) {
			return !in_array($record->postId, $existingIds);
		});
		$this->logger->debug('chunk contains ' . count($newRecords) . ' new liked vines');

		$this->saveNewVines($newRecords, $user->getUID());
	}

	/**
	 * @param array $records
	 * @param string $userId
	 */
	private function saveNewVines(array $records, $userId) {
		foreach ($records as $record) {
			$vine = $this->createVine($record, $userId);
			$this->mapper->insert($vine);
		}
	}

	/**
	 * @param object $record
	 * @param string $userId
	 * @return Vine
	 */
	private function createVine($record, $userId) {
		$vine = new Vine();
		$vine->setId($record->postId);
		$vine->setUserId($userId);
		$vine->setDescription($record->description);
		$vine->setPermalink($record->permalinkUrl);
		$vine->setVineUserId($record->userId);
		$vine->setUsername($record->username);
		$vine->setVideoUrl($record->videoUrl);
		$vine->setDownloaded((int) false);
		return $vine;
	}

}
