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

use OCP\ILogger;

class Logger {

	/** @var ILogger */
	private $logger;

	public function __construct(ILogger $logger) {
		$this->logger = $logger;
	}

	public function alert($message, array $context = array()) {
		$this->logger->alert($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function critical($message, array $context = array()) {
		$this->logger->critical($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function debug($message, array $context = array()) {
		$this->logger->debug($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function emergency($message, array $context = array()) {
		$this->logger->emergency($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function error($message, array $context = array()) {
		$this->logger->error($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function info($message, array $context = array()) {
		$this->logger->info($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function log($level, $message, array $context = array()) {
		$this->logger->log($level, $message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function logException($exception, array $context = array()) {
		$this->logger->logException($exception, array_merge($context, ['app' => 'vinecellar']));
	}

	public function notice($message, array $context = array()) {
		$this->logger->notice($message, array_merge($context, ['app' => 'vinecellar']));
	}

	public function warning($message, array $context = array()) {
		$this->logger->warning($message, array_merge($context, ['app' => 'vinecellar']));
	}

}
