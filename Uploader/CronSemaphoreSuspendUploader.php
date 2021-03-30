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

class CronSemaphoreSuspendUploader implements UploaderInterface
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
     * @param string $uploaderType
     * @throws TransporterException
     */
    public function execute(int $activityId, string $uploaderType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ forSeconds limit:%3 ~ START',
            $activityId,
            $uploaderType,
            $this->forSeconds
        ));

        try {
            $this->cronSempahoreManager->suspend($this->forSeconds);
        } catch (Exception $e) {
            throw new TransporterException(__(
                'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ forSeconds limit:%3 ~ ERROR',
                $activityId,
                $uploaderType,
                $this->forSeconds
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ forSeconds limit:%3 ~ END',
            $activityId,
            $uploaderType,
            $this->forSeconds
        ));
    }
}
