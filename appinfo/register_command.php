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
use OC;
use OCA\VineCellar\AppInfo\Application;
use OCA\VineCellar\Command\DownloadAllCommand;
use OCA\VineCellar\Service\VineDownloader;
use Symfony\Component\Console\Application as Application2;

$app = new Application();
$vineDownloader = $app->getContainer()->query(VineDownloader::class);
$userManger = OC::$server->query('UserManager');

/** @var Application2 $application */
$application->add(new DownloadAllCommand($vineDownloader, $userManger));
