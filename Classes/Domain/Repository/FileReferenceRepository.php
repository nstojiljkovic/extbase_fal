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
 * Class FileReferenceRepository
 *
 * @package EssentialDots\ExtbaseFal\Domain\Repository
 * @author Nikola Stojiljkovic
 */
class FileReferenceRepository extends AbstractFileRepository {

	/**
	 * @var array default ordering
	 */
	protected $defaultOrderings = array(
		'sorting_foreign' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
	);

	/**
	 * Injects query settings object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings The Query Settings
	 * @return void
	 */
	public function injectQuerySettings(\TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings) {
		$querySettings->setRespectStoragePage(FALSE);
		if ($this->getDoNotRespectEnableFields()) {
			$querySettings->setIgnoreEnableFields(TRUE);
		}
		$this->setDefaultQuerySettings($querySettings);
	}

	/**
	 * @param $foreignUid
	 * @param $tableName
	 * @param $fieldName
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByForeignUid($foreignUid, $tableName, $fieldName) {

		$query = $this->createQuery(); /* @var $query \TYPO3\CMS\Extbase\Persistence\Generic\Query */

		$sysFileReferenceEnableFields = $this->getEnabledFields('sys_file_reference');
		$sysFileEnableFields = $this->getEnabledFields('sys_file');

		$statement = '
			# @tables_used=sys_file,sys_file_reference;

			SELECT
				sys_file_reference.*
			FROM
				sys_file
				INNER JOIN sys_file_reference ON (
					sys_file.uid = sys_file_reference.uid_local AND
					sys_file_reference.tablenames = ' . $this->getDatabase()->fullQuoteStr($tableName, 'sys_file_reference') . ' AND
					sys_file_reference.fieldname = ' . $this->getDatabase()->fullQuoteStr($fieldName, 'sys_file_reference') . ' AND
					sys_file_reference.table_local = \'sys_file\')
			WHERE
				sys_file_reference.uid_foreign = ' . intval($foreignUid) . '
				' . $sysFileReferenceEnableFields . '
				' . $sysFileEnableFields . '
			ORDER BY sorting_foreign ASC';

		$query->statement($statement);

		return $query->execute();
	}

	/**
	 * @param $fileUid
	 * @param $foreignUid
	 * @param $tableName
	 * @param $fieldName
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function existsByForeignUid($fileUid, $foreignUid, $tableName, $fieldName) {
		$exists = FALSE;

		$sysFileReferenceEnableFields = $this->getEnabledFields('sys_file_reference');
		$sysFileEnableFields = $this->getEnabledFields('sys_file');

		$statement = '
			# @tables_used=sys_file,sys_file_reference;

			SELECT
				COUNT(*) as cnt
			FROM
				sys_file
				INNER JOIN sys_file_reference ON (
					sys_file.uid = sys_file_reference.uid_local AND
					sys_file_reference.tablenames = ' . $this->getDatabase()->fullQuoteStr($tableName, 'sys_file_reference') . ' AND
					sys_file_reference.fieldname = ' . $this->getDatabase()->fullQuoteStr($fieldName, 'sys_file_reference') . ' AND
					sys_file_reference.table_local = \'sys_file\')
			WHERE sys_file_reference.uid_foreign = ' . intval($foreignUid) . '
				AND sys_file_reference.uid_local = ' . intval($fileUid) . '
				' . $sysFileReferenceEnableFields . '
				' . $sysFileEnableFields . '
			ORDER BY sorting_foreign ASC';

		$res = $this->getDatabase()->sql_query($statement);

		if ($res && ($row = $this->getDatabase()->sql_fetch_assoc($res))) {
			if ($row['cnt']) {
				$exists = TRUE;
			}
		}

		return $exists;
	}
}
