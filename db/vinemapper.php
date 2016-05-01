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

use OCP\AppFramework\Db\Mapper;
use OCP\IDBConnection;

class VineMapper extends Mapper {

	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'vinecellar_vines');
	}

	/**
	 * @param string $userId
	 * @param int[] $ids
	 */
	public function findByIds($userId, $ids) {
		$sql = 'SELECT `id`, `user_id`, `description`, `permalink`, '
			. '`vine_user_id`, `username`, `video_url`, `downloaded` '
			. 'FROM ' . $this->getTableName() . ' '
			. 'WHERE `user_id` = ? '
			. 'AND `id` IN ( ' . implode(',', $ids) . ' )'; // TODO: find better solution

		return $this->findEntities($sql, [
				$userId
		]);
	}

	public function findNotDownloadedVines($userId, $count) {
		$sql = 'SELECT `id`, `user_id`, `description`, `permalink`, '
			. '`vine_user_id`, `username`, `video_url`, `downloaded` '
			. 'FROM ' . $this->getTableName() . ' '
			. 'WHERE `user_id` = ? '
			. 'AND `downloaded` = 0';

		return $this->findEntities($sql, [
				$userId,
		], $count);
	}

}
