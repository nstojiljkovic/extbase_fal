<?php
namespace EssentialDots\ExtbaseFal\DataHandling;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

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
 * Class DataHandler
 *
 * @package EssentialDots\ExtbaseFal\DataHandling
 * @author Nikola Stojiljkovic
 */
class DataHandler extends \TYPO3\CMS\Core\DataHandling\DataHandler {

	/**
	 * Handling files for group/select function
	 *
	 * @param array $valueArray Array of incoming file references. Keys are numeric, values are files (basically, this is the exploded list of incoming files)
	 * @param array $tcaFieldConf Configuration array from TCA of the field
	 * @param string $curValue Current value of the field
	 * @param array $uploadedFileArray Array of uploaded files, if any
	 * @param string $status Status ("update" or ?)
	 * @param string $table tablename of record
	 * @param integer $id UID of record
	 * @param string $recFID Field identifier ([table:uid:field:....more for flexforms?]
	 * @return array Modified value array
	 * @see checkValue_group_select()
	 */
	// @codingStandardsIgnoreStart
	public function checkValue_group_select_file($valueArray, $tcaFieldConf, $curValue, $uploadedFileArray, $status, $table, $id, $recFID) {
		$oldBypassFileHandling = $this->bypassFileHandling;
		if (isset($tcaFieldConf['bypassFileHandling'])) {
			$this->bypassFileHandling = $tcaFieldConf['bypassFileHandling'];
		}
		$result = parent::checkValue_group_select_file($valueArray, $tcaFieldConf, $curValue, $uploadedFileArray, $status, $table, $id, $recFID);
		$this->bypassFileHandling = $oldBypassFileHandling;

		return $result;
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Modifying a field value for any situation regarding files/references:
	 * For attached files: take current filenames and prepend absolute paths so they get copied.
	 * For DB references: Nothing done.
	 *
	 * @param array $conf TCE field config
	 * @param integer $uid Record UID
	 * @param string $value Field value (eg. list of files)
	 * @return string The (possibly modified) value
	 * @see copyRecord(), copyRecord_flexFormCallBack()
	 */
	// @codingStandardsIgnoreStart
	public function copyRecord_procFilesRefs($conf, $uid, $value) {
		$result = NULL;

		if ($conf['bypassFileHandling']) {
			$result = $value;
		} else {
			$result = parent::copyRecord_procFilesRefs($conf, $uid, $value);
		}

		return $result;
	}
	// @codingStandardsIgnoreEnd
}