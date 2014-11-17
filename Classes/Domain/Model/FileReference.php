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
 * Class FileReference
 *
 * @package EssentialDots\ExtbaseFal\Domain\Model
 * @author Nikola Stojiljkovic
 */
class FileReference extends AbstractFileReference {

	/**
	 * @var \EssentialDots\ExtbaseFal\Domain\Model\File
	 */
	protected $file;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $alternative;

	/**
	 * @var string
	 */
	protected $link;

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

	/**
	 * @param string $alternative
	 * @return void
	 */
	public function setAlternative($alternative) {
		$this->alternative = $alternative;
	}

	/**
	 * @return string
	 */
	public function getAlternative() {
		return $this->alternative ? $this->alternative : $this->file->getAlternative();
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
		return $this->description ? $this->description : $this->file->getDescription();
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
		return $this->title ? $this->title : $this->file->getTitle();
	}

	/**
	 * @param string $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * @param \EssentialDots\ExtbaseFal\Domain\Model\File $file
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * @return \EssentialDots\ExtbaseFal\Domain\Model\File
	 */
	public function getFile() {
		return $this->file;
	}
}