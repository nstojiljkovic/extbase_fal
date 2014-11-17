<?php
namespace EssentialDots\ExtbaseFal\Domain\Model;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Class FileMetaData
 *
 * @package EssentialDots\ExtbaseFal\Domain\Model
 * @author Nikola Stojiljkovic
 */
class FileMetaData extends \EssentialDots\ExtbaseDomainDecorator\DomainObject\AbstractEntity {

	/**
	 * @var \EssentialDots\ExtbaseFal\Domain\Model\AbstractFile
	 */
	protected $file;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var int
	 */
	protected $width;

	/**
	 * @var int
	 */
	protected $height;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $alternative;

	/**
	 * categories
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
	 */
	protected $categories;

	/**
	 * @var \EssentialDots\ExtbaseFal\Domain\Repository\FileReferenceRepository
	 */
	// @codingStandardsIgnoreStart
	protected $_fileReferenceRepository;
	// @codingStandardsIgnoreEnd

	/**
	 * @param $decoratedObject
	 */
	public function __construct($decoratedObject = NULL) {
		parent::__construct($decoratedObject);
	}

	/**
	 * Adds a NewsCategory
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category) {
		$this->categories->attach($category);
	}

	/**
	 * Removes a NewsCategory
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The NewsCategory to be removed
	 * @return void
	 */
	public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove) {
		$this->categories->detach($categoryToRemove);
	}
	/**
	 * Returns the categories
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @param string $alternative
	 */
	public function setAlternative($alternative) {
		$this->alternative = $alternative;
	}

	/**
	 * @return string
	 */
	public function getAlternative() {
		return $this->alternative;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param int $height
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title ? $this->title : $this->getName();
	}

	/**
	 * @param int $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param \EssentialDots\ExtbaseFal\Domain\Model\AbstractFile $file
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * @return \EssentialDots\ExtbaseFal\Domain\Model\AbstractFile
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @return \EssentialDots\ExtbaseFal\Domain\Repository\FileReferenceRepository $fileReferenceRepository
	 */
	public function getFileReferenceRepository() {
		if (!$this->_fileReferenceRepository) {
			$this->_fileReferenceRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')->get('EssentialDots\\ExtbaseFal\\Domain\\Repository\\FileReferenceRepository');
		}

		return $this->_fileReferenceRepository;
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed|null
	 */
	public function __call($name, $arguments) {
		$result = NULL;

		if ($this->file) {
			$result = call_user_func_array(array($this->file, $name), $arguments);
		} else {
			error_log('Method doesn\'t exists ' . get_class($this) . '::' . $name);
		}

		return $result;
	}
}
