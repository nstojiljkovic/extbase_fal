<?php
namespace EssentialDots\ExtbaseFal\Resource\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Essential Dots d.o.o. Belgrade
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Resource;
use TYPO3\CMS\Core\Utility;

/**
 * Class FileProcessingService
 *
 * @package EssentialDots\ExtbaseFal\Resource\Service
 * @author Nikola Stojiljkovic
 */
class FileProcessingService extends \TYPO3\CMS\Core\Resource\Service\FileProcessingService {

	/**
	 * @var \EssentialDots\ExtbaseHijax\Lock\LockManager
	 */
	protected $lockManager;

	/**
	 * @var \TYPO3\CMS\Core\Resource\ProcessedFileRepository
	 */
	protected $processedFileRepository;

	/**
	 * Processes the file
	 *
	 * @param Resource\ProcessedFile $processedFile
	 * @param Resource\ResourceStorage $targetStorage
	 * @return void
	 */
	protected function process(Resource\ProcessedFile $processedFile, Resource\ResourceStorage $targetStorage) {
		$processedFileHash = $processedFile->calculateChecksum();
		$sharedLock = NULL;
		$sharedLockAcquired = $this->getLockManager()->acquireLock($sharedLock, '_processed_file_s_' . $processedFileHash, FALSE);

		if ($sharedLockAcquired && $sharedLock) {
			try {
				$newProcessedFile = $this->getProcessedFileRepository()->findOneByOriginalFileAndTaskTypeAndConfiguration(
					$processedFile->getOriginalFile(), $processedFile->getTask()->getType(), $processedFile->getTask()->getConfiguration());
				if (!$newProcessedFile->isNew()) {
					$processedFile->updateProperties($newProcessedFile->getProperties());
				}

				if ($processedFile->isNew() || (!$processedFile->usesOriginalFile() && !$processedFile->exists()) || $processedFile->isOutdated()) {
					$exclusiveLock = NULL;
					$exclusiveLockAcquired = $this->getLockManager()->acquireLock($exclusiveLock, '_processed_file_e_' . $processedFileHash, TRUE);

					if ($exclusiveLockAcquired && $exclusiveLock) {
						try {
							$newProcessedFile = $this->getProcessedFileRepository()->findOneByOriginalFileAndTaskTypeAndConfiguration(
								$processedFile->getOriginalFile(), $processedFile->getTask()->getType(), $processedFile->getTask()->getConfiguration());
							if (!$newProcessedFile->isNew()) {
								$processedFile->updateProperties($newProcessedFile->getProperties());
							}

							if ($processedFile->isNew() || (!$processedFile->usesOriginalFile() && !$processedFile->exists()) || $processedFile->isOutdated()) {
								parent::process($processedFile, $targetStorage);
							}
						} catch (\Exception $e) {
							error_log($e->getMessage());
						}

						$this->getLockManager()->releaseLock($exclusiveLock);
					} else {
						error_log('Failed acquiring of exclusive lock, method: FileProcessingService->process.');
						// throw exception here maybe?
					}
				}
			} catch (\Exception $e) {
				error_log($e->getMessage());
			}

			$this->getLockManager()->releaseLock($sharedLock);
		} else {
			error_log('Failed acquiring of shared lock, method: FileProcessingService->process.');
			// throw exception here maybe?
		}
	}

	/**
	 * @return \EssentialDots\ExtbaseHijax\Lock\LockManager|object
	 */
	protected function getLockManager() {
		if (!$this->lockManager) {
			$this->lockManager = Utility\GeneralUtility::makeInstance('EssentialDots\\ExtbaseHijax\\Lock\\LockManager');
		}
		return $this->lockManager;
	}

	/**
	 * @return Resource\ProcessedFileRepository
	 */
	protected function getProcessedFileRepository() {
		if (!$this->processedFileRepository) {
			$this->processedFileRepository = Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ProcessedFileRepository');
		}
		return $this->processedFileRepository;
	}
}
