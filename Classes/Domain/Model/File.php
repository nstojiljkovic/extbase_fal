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
class File extends AbstractFile {

	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceInterface|\TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\AbstractFile
	 */
	// @codingStandardsIgnoreStart
	protected $_fileObject;
	// @codingStandardsIgnoreEnd

	/**
	 * @var string
	 */
	protected $storage;

	/**
	 * @var string
	 */
	protected $identifier;

	/**
	 * @var string
	 */
	protected $extension;

	/**
	 * @var string
	 */
	protected $mimeType;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $sha1;

	/**
	 * @var int
	 */
	protected $size;

	/**
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * @var \DateTime
	 */
	protected $modificationDate;

	/**
	 * @var \DateTime
	 */
	protected $lastIndexed;

	/**
	 * @var bool
	 */
	protected $missing;

	/**
	 * @var string
	 */
	protected $identifierHash;

	/**
	 * @var string
	 */
	protected $folderHash;

	/**
	 * @var \EssentialDots\ExtbaseFal\Domain\Repository\FileReferenceRepository
	 * @inject
	 */
	protected $fileReferenceRepository;

	/**
	 * metadata
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EssentialDots\ExtbaseFal\Domain\Model\FileMetaData>
	 */
	protected $metadata;

	/**
	 * @param null $decoratedObject
	 */
	public function __construct($decoratedObject = NULL) {
		parent::__construct($decoratedObject);
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->metadata = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * @param \EssentialDots\ExtbaseFal\Domain\Model\FileMetaData $metadata
	 * @return void
	 */
	public function addMetadata(\EssentialDots\ExtbaseFal\Domain\Model\FileMetaData $metadata) {
		$this->metadata->attach($metadata);
	}

	/**
	 * @param \EssentialDots\ExtbaseFal\Domain\Model\FileMetaData $metadataToRemove The NewsCategory to be removed
	 * @return void
	 */
	public function removeMetadata(\EssentialDots\ExtbaseFal\Domain\Model\FileMetaData $metadataToRemove) {
		$this->metadata->detach($metadataToRemove);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EssentialDots\ExtbaseFal\Domain\Model\FileMetaData> $metadata
	 */
	public function getMetadata() {
		return $this->metadata;
	}

	/**
	 * @return \EssentialDots\ExtbaseFal\Domain\Model\FileMetaData $metadata
	 */
	public function getCurrentMetadata() {
		foreach ($this->getMetadata() as $metadata) {
			// @todo: implement actual logic which will choose based on the language and workspace, when that feature is actually implemented in the TYPO3 core
			// in TYPO3 6.2 there's only one meta data record for each file
			return $metadata;
		}

		return NULL;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @param string $extension
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
	}

	/**
	 * @return string
	 */
	public function getExtension() {
		return $this->extension ? $this->extension : pathinfo($this->getForLocalProcessing(), PATHINFO_EXTENSION);
	}

	/**
	 * @param string $identifier
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * @param \DateTime $modificationDate
	 */
	public function setModificationDate($modificationDate) {
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $sha1
	 */
	public function setSha1($sha1) {
		$this->sha1 = $sha1;
	}

	/**
	 * @return string
	 */
	public function getSha1() {
		return $this->sha1;
	}

	/**
	 * @param int $size
	 */
	public function setSize($size) {
		$this->size = $size;
	}

	/**
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param string $folderHash
	 */
	public function setFolderHash($folderHash) {
		$this->folderHash = $folderHash;
	}

	/**
	 * @return string
	 */
	public function getFolderHash() {
		return $this->folderHash;
	}

	/**
	 * @param string $identifierHash
	 */
	public function setIdentifierHash($identifierHash) {
		$this->identifierHash = $identifierHash;
	}

	/**
	 * @return string
	 */
	public function getIdentifierHash() {
		return $this->identifierHash;
	}

	/**
	 * @param \DateTime $lastIndexed
	 */
	public function setLastIndexed($lastIndexed) {
		$this->lastIndexed = $lastIndexed;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastIndexed() {
		return $this->lastIndexed;
	}

	/**
	 * @param boolean $missing
	 */
	public function setMissing($missing) {
		$this->missing = $missing;
	}

	/**
	 * @return boolean
	 */
	public function getMissing() {
		return $this->missing;
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
	 * @return \TYPO3\CMS\Core\Resource\ResourceInterface|\TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\AbstractFile
	 */
	public function getFileObject() {
		if (!$this->_fileObject) {
			$row = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
				'*',
				'sys_file',
				'uid=' . (int) $this->getUid()
			);
			$this->_fileObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileObject($this->getUid(), $row);
		}
		return $this->_fileObject;
	}

	/**
	 * Gets database instance
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Returns a path to a local version of this file to process it locally (e.g. with some system tool).
	 * If the file is normally located on a remote storages, this creates a local copy.
	 * If the file is already on the local system, this only makes a new copy if $writable is set to TRUE.
	 *
	 * @param boolean $writable Set this to FALSE if you only want to do read operations on the file.
	 * @return string
	 */
	public function getForLocalProcessing($writable = FALSE) {
		$result = NULL;

		if ($this->getFileObject()) {
			$forLocalProcessing = $this->getFileObject()->getForLocalProcessing($writable);
			$forLocalProcessing = str_replace(PATH_site, '', $forLocalProcessing);
			$result = $forLocalProcessing;
		}

		return $result;
	}

	/**
	 * Returns a path to a local version of this file to process it locally (e.g. with some system tool).
	 * If the file is normally located on a remote storages, this creates a local copy.
	 * If the file is already on the local system, this only makes a new copy if $writable is set to TRUE.
	 *
	 * @return string
	 */
	public function getPublicUrl() {
		$result = NULL;

		if ($this->getFileObject()) {
			$result = $this->getFileObject()->getPublicUrl();
		}

		return $result;
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed|null
	 */
	public function __call($name, $arguments) {
		if ($this->getCurrentMetadata()) {
			if (method_exists($this->getCurrentMetadata(), $name)) {
				return call_user_func_array(array($this->getCurrentMetadata(), $name), $arguments);
			} else {
				error_log('Method doesn\'t exists ' . get_class($this) . '::' . $name);
			}
		//} else {
			//error_log("No meta data for record {$this->getUid()}");
		}
		return NULL;
	}
}