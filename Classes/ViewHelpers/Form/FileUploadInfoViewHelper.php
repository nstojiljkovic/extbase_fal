<?php
namespace EssentialDots\ExtbaseFal\ViewHelpers\Form;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Essential Dots d.o.o. Belgrade
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
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class FileUploadInfoViewHelper
 *
 * @package EssentialDots\ExtbaseFal\ViewHelpers\Form
 */
class FileUploadInfoViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {

	/**
	 * @var \EssentialDots\ExtbaseFal\Domain\Repository\FileRepository
	 * @inject
	 */
	protected $fileRepository;

	/**
	 * @param string $returnField
	 * @return mixed|string
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileException
	 */
	public function render($returnField = 'uid') {
		$value =  $this->arguments['value'];

		if ($value) {
			$file = $this->fileRepository->findByUid($value);
			if ($file instanceof \EssentialDots\ExtbaseFal\Domain\Model\File) {
				return ObjectAccess::getProperty($file, $returnField);
			}
		}

		return '';
	}
}
