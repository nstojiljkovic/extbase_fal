<?php
namespace EssentialDots\ExtbaseFal\Domain\Model;

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

/**
 * Class File
 *
 * @package EssentialDots\ExtbaseFal\Domain\Model
 * @author Nikola Stojiljkovic
 */
class Folder extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var \TYPO3\CMS\Core\Resource\Folder
	 */
	protected $folderObject;

	/**
	 * @var string
	 */
	protected $storage;

	/**
	 * files
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EssentialDots\ExtbaseFal\Domain\Model\File>
	 */
	protected $files;

	/**
	 * @param null $decoratedObject
	 */
	public function __construct($folderObject = NULL) {
		$this->folderObject = $folderObject;
		$this->uid = $this->folderObject->getCombinedIdentifier();
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {

	}

	/**
	 * Getter for uid.
	 *
	 * @return integer the uid or NULL if none set yet.
	 */
	public function getUid() {
		return ($this->uid !== NULL) ? (string) $this->uid : NULL;
	}

	/**
	 * @param \EssentialDots\ExtbaseFal\Domain\Model\File $file
	 * @return void
	 */
	public function addFile(\EssentialDots\ExtbaseFal\Domain\Model\File $file) {
		$this->files->attach($file);
	}

	/**
	 * @param \EssentialDots\ExtbaseFal\Domain\Model\File $fileToRemove
	 * @return void
	 */
	public function removeFile(\EssentialDots\ExtbaseFal\Domain\Model\File $fileToRemove) {
		$this->files->detach($fileToRemove);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EssentialDots\ExtbaseFal\Domain\Model\File>
	 */
	public function getFiles() {
		$this->files = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		foreach ($this->folderObject->getFiles() as $fileObject) {
			/** @var $file $fileObject */

			/** @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManagerInterface */
			$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
			/** @var $fileRepository \EssentialDots\ExtbaseFal\Domain\Repository\FileRepository */
			$fileRepository = $objectManager->get('EssentialDots\\ExtbaseFal\\Domain\\Repository\\FileRepository');
			$file = $fileRepository->findByUid($fileObject->getUid());
			$this->files->attach($file);
		}
		return $this->files;
	}

	/**
	 * @param string $storage
	 */
	public function setStorage($storage) {
		$this->storage = $storage;
	}

	/**
	 * @return string
	 */
	public function getStorage() {
		return $this->storage;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->folderObject->getName();
	}

	/**
	 * @return null|\TYPO3\CMS\Core\Resource\Folder
	 */
	public function getFolderResource() {
		return $this->folderObject;
	}
}