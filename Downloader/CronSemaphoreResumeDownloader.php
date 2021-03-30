<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCronSemaphore\Downloader;

use Exception;
use Monolog\Logger;
use Websolute\CronSemaphore\Api\CronSempahoreManagerInterface;
use Websolute\TransporterBase\Api\DownloaderInterface;
use Websolute\TransporterBase\Exception\TransporterException;

class CronSemaphoreResumeDownloader implements DownloaderInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CronSempahoreManagerInterface
     */
    private $cronSempahoreManager;

    /**
     * @param Logger $logger
     * @param CronSempahoreManagerInterface $cronSempahoreManager
     */
    public function __construct(
        Logger $logger,
        CronSempahoreManagerInterface $cronSempahoreManager
    ) {
        $this->logger = $logger;
        $this->cronSempahoreManager = $cronSempahoreManager;
    }

    /**
     * @param int $activityId
     * @param string $downloaderType
     * @throws TransporterException
     */
    public function execute(int $activityId, string $downloaderType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ START',
            $activityId,
            $downloaderType
        ));

        try {
            $this->cronSempahoreManager->resume();
        } catch (Exception $e) {
            throw new TransporterException(__(
                'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ ERROR',
                $activityId,
                $downloaderType
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ END',
            $activityId,
            $downloaderType
        ));
    }
}
