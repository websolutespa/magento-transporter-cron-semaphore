<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCronSemaphore\Uploader;

use Exception;
use Monolog\Logger;
use Websolute\CronSemaphore\Api\CronSempahoreManagerInterface;
use Websolute\TransporterBase\Api\UploaderInterface;
use Websolute\TransporterBase\Exception\TransporterException;

class CronSemaphoreResumeUploader implements UploaderInterface
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
     * @param string $uploaderType
     * @throws TransporterException
     */
    public function execute(int $activityId, string $uploaderType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ START',
            $activityId,
            $uploaderType
        ));

        try {
            $this->cronSempahoreManager->resume();
        } catch (Exception $e) {
            throw new TransporterException(__(
                'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ ERROR',
                $activityId,
                $uploaderType
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ END',
            $activityId,
            $uploaderType
        ));
    }
}
