<?php
namespace EssentialDots\ExtbaseFal\Resource\Index;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Essential Dots d.o.o. Belgrade
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
 * Class Indexer
 *
 * @package EssentialDots\ExtbaseFal\Resource\Indexer
 */
class Indexer extends \TYPO3\CMS\Core\Resource\Index\Indexer {

	/**
	 * Runs the metadata extraction for a given file.
	 *
	 * @param \TYPO3\CMS\Core\Resource\File $fileObject
	 * @return void
	 * @see \TYPO3\CMS\Core\Resource\Index\Indexer::runMetaDataExtraction
	 */
	protected function runMetaDataWithExtractionForFile(\TYPO3\CMS\Core\Resource\File $fileObject) {
		$extractionServices = $this->getExtractorRegistry()->getExtractorsWithDriverSupport($this->storage->getDriverType());
		if (count($extractionServices) === 0) {
			return;
		}

		$newMetaData = array(
			0 => $fileObject->_getMetaData()
		);
		foreach ($extractionServices as $service) {
			if ($service->canProcess($fileObject)) {
				$newMetaData[$service->getPriority()] = $service->extractMetaData($fileObject, $newMetaData);
			}
		}
		ksort($newMetaData);
		$metaData = array();
		foreach ($newMetaData as $data) {
			$metaData = array_merge($metaData, $data);
		}
		$fileObject->_updateMetaDataProperties($metaData);
		$metaDataRepository = \TYPO3\CMS\Core\Resource\Index\MetaDataRepository::getInstance();
		$metaDataRepository->update($fileObject->getUid(), $metaData);
	}

	/**
	 * Since the core desperately needs image sizes in metadata table put them there
	 * This should be called after every "content" update and "record" creation
	 *
	 * @param \TYPO3\CMS\Core\Resource\File $fileObject
	 * @return void
	 */
	protected function extractRequiredMetaData(\TYPO3\CMS\Core\Resource\File $fileObject) {
		if ($fileObject->getType() == \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE ) {
			if ($this->storage->getDriverType() === 'Local') {
				parent::extractRequiredMetaData($fileObject);
			} else {
				$this->runMetaDataWithExtractionForFile($fileObject);
			}
		}
	}
}
