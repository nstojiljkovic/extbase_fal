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

/**
 * Class AbstractFileRepository
 *
 * @package EssentialDots\ExtbaseFal\Domain\Repository
 * @author Nikola Stojiljkovic
 */
abstract class AbstractFileRepository extends \EssentialDots\ExtbaseDomainDecorator\Persistence\AbstractRepository {

	/**
	 * @param string $tableName
	 * @return string
	 */
	protected function getEnabledFields($tableName = '') {
		if ($tableName == 'sys_file' && $this->getDoNotRespectEnableFields()) {
			$statement = '';
		} elseif (TYPO3_MODE === 'FE') {
			$statement = $GLOBALS['TSFE']->sys_page->enableFields($tableName);
		} else {
			// TYPO3_MODE === 'BE'
			$statement = \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause($tableName);
			$statement .= \TYPO3\CMS\Backend\Utility\BackendUtility::BEenableFields($tableName);
		}

		return $statement;
	}

	/**
	 * Get the database object
	 *
	 * @access protected
	 * @see t3lib_db
	 * @return t3lib_db
	 */
	protected function getDatabase() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @var null|bool
	 */
	protected $doNotRespectEnableFields = NULL;

	/**
	 * @return string
	 */
	protected function getDoNotRespectEnableFields() {
		if ($this->doNotRespectEnableFields == NULL) {
			$configurationManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager'); /* @var $configurationManager \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
			$settings = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);
			$this->doNotRespectEnableFields = (bool) $settings['doNotRespectEnableFields'];
		}

		return $this->doNotRespectEnableFields;
	}
}