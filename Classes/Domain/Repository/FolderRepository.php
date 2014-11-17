<?php
namespace EssentialDots\ExtbaseFal\Domain\Repository;

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
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Folder
 *
 * @package EssentialDots\ExtbaseFal\Domain\Repository
 * @author Nikola Stojiljkovic
 */
class FolderRepository extends \EssentialDots\ExtbaseDomainDecorator\Persistence\AbstractRepository {

	/**
	 * Expects uid in the following forms:
	 * 1. '1:/folder1/folder2/...'
	 * 2. '1:%2ffolder1%2ffolder2%2f...'
	 * 3. base64 encoded
	 * Also, expects the folder name to already be sanitized!
	 *
	 * @param int $uid
	 * @param bool $createFolders
	 * @return null|\EssentialDots\ExtbaseFal\Domain\Model\Folder
	 */
	public function findByUid($uid, $createFolders = FALSE) {
		$result = NULL;
		$decodedUid = $this->getDecodedUid($uid, $createFolders);
		if ($decodedUid) {
			$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
			$folder = $resourceFactory->retrieveFileOrFolderObject($decodedUid);

			if ($folder instanceof Folder) {
				$result = GeneralUtility::makeInstance('EssentialDots\\ExtbaseFal\\Domain\\Model\\Folder', $folder);
			}
		}

		return $result;
	}

	/**
	 * @param $uid
	 * @param bool $createFolders
	 *
	 * @return NULL|string
	 */
	protected function getDecodedUid($uid, $createFolders = FALSE) {
		$decodedUid = NULL;
		if (preg_match('/\d+:\/.*/', $uid) !== 1) {
			$uid2 = base64_decode($uid);
			if (preg_match('/\d+:\/.*/', $uid2) !== 1) {
				$uid = str_replace('%2f', '/', $uid);
			} else {
				$uid = $uid2;
			}
		}

		$matches = NULL;
		if (preg_match('/(\d+):\/(.*)/', $uid, $matches) === 1) {
			$storageUid = $matches[1];
			$requiredFullPath = $matches[2];
			$fullPathArr = GeneralUtility::trimExplode(DIRECTORY_SEPARATOR, $matches[2], TRUE);
			$sanitizedFullPathArr = array();

			$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
			$storage = $resourceFactory->getStorageObject($storageUid);
			$driverObject = $resourceFactory->getDriverObject($storage->getDriverType(), $storage->getConfiguration());

			foreach ($fullPathArr as $segment) {
				$sanitizedSegment = $driverObject->sanitizeFileName($segment);
				if ($sanitizedSegment) {
					$sanitizedFullPathArr[] = $sanitizedSegment;
				}
			}

			$sanitizedFullPath = implode(DIRECTORY_SEPARATOR, $sanitizedFullPathArr);

			if ($sanitizedFullPath === $requiredFullPath || $sanitizedFullPath . DIRECTORY_SEPARATOR === $requiredFullPath) {
				$decodedUid = $storageUid . ':/' . $sanitizedFullPath;

				if ($createFolders) {
					$folder = $storage->getRootLevelFolder();
					foreach ($fullPathArr as $segment) {
						if (!$folder->hasFolder($segment)) {
							$folder = $folder->createFolder($segment);
						} else {
							$folder = $folder->getSubfolder($segment);
						}
					}
				}
			}
		}

		return $decodedUid;
	}
}
