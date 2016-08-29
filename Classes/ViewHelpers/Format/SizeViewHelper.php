<?php
namespace EssentialDots\ExtbaseFal\ViewHelpers\Format;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SizeViewHelper
 *
 * @package EssentialDots\ExtbaseFal\ViewHelpers\Format
 */
class SizeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Get size from file
	 *
	 * @param string $labels
	 * @return string
	 */
	public function render($labels = ' | KB| MB| GB') {
		return GeneralUtility::formatSize((int) $this->renderChildren(), $labels);
	}
}
