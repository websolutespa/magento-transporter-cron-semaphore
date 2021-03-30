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

class CronSemaphoreSuspendDownloader implements DownloaderInterface
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
     * @var int
     */
    private $forSeconds;

    /**
     * @param Logger $logger
     * @param CronSempahoreManagerInterface $cronSempahoreManager
     * @param int $forSeconds
     */
    public function __construct(
        Logger $logger,
        CronSempahoreManagerInterface $cronSempahoreManager,
        int $forSeconds = CronSempahoreManagerInterface::TIMEOUT_EXPIRE
    ) {
        $this->logger = $logger;
        $this->cronSempahoreManager = $cronSempahoreManager;
        $this->forSeconds = $forSeconds;
    }

    /**
     * @param int $activityId
     * @param string $downloaderType
     * @throws TransporterException
     */
    public function execute(int $activityId, string $downloaderType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ forSeconds limit:%3 ~ START',
            $activityId,
            $downloaderType,
            $this->forSeconds
        ));

        try {
            $this->cronSempahoreManager->suspend($this->forSeconds);
        } catch (Exception $e) {
            throw new TransporterException(__(
                'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ forSeconds limit:%3 ~ ERROR',
                $activityId,
                $downloaderType,
                $this->forSeconds
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ forSeconds limit:%3 ~ END',
            $activityId,
            $downloaderType,
            $this->forSeconds
        ));
    }
}
