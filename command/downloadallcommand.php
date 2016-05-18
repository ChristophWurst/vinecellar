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

namespace OCA\VineCellar\Command;

use OCA\VineCellar\Service\VineDownloader;
use OCP\IUserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadAllCommand extends Command {

	/** @var VineDownloader */
	private $downloader;

	/** @var IUserManager */
	private $userManager;

	/**
	 * @param VineDownloader $downloader
	 * @param IUserManager $userManager
	 */
	public function __construct(VineDownloader $downloader, IUserManager $userManager) {
		parent::__construct();
		$this->downloader = $downloader;
		$this->userManager = $userManager;
	}

	protected function configure() {
		$this->setName('vinecellar:download:all');
		$this->addArgument('user', InputArgument::REQUIRED);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function run(InputInterface $input, OutputInterface $output) {
		$output->writeln("a");
		$uid = $input->getArgument('user');
		$user = $this->userManager->get($uid);
		$this->downloader->downloadUsersVines($user);
	}

}
