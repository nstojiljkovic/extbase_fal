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
 * Class FileRepository
 *
 * @package EssentialDots\ExtbaseFal\Domain\Repository
 * @author Nikola Stojiljkovic
 */
class FileRepository extends AbstractFileRepository {

	/**
	 * @var array default ordering
	 */
	protected $defaultOrderings = array();

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
	 * Finds an object matching the given identifier.
	 *
	 * @param int $uid The identifier of the object to find
	 * @return object The matching object if found, otherwise NULL
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileException
	 */
	public function findByUid($uid) {
		if (!\TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($uid)) {
			throw new \TYPO3\CMS\Core\Resource\Exception\InvalidFileException(
				'Finding files by path is not supported. Please migrate the logic from EssentialDots\\ExtbaseFal\\Domain\\TYPO361\\Repository\\FileRepository.', 9874732);
		}

		return $this->findByIdentifier($uid);
	}

	/**
	 * @param $foreignUid
	 * @param $tableName
	 * @param $fieldName
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByReference($foreignUid, $tableName, $fieldName) {

		$query = $this->createQuery();
		/* @var $query Tx_Extbase_Persistence_Query */

		$sysFileReferenceEnableFields = $this->getEnabledFields('sys_file_reference');
		$sysFileEnableFields = $this->getEnabledFields('sys_file');
		$metaDataEnableFields = $this->getEnabledFields('sys_file_metadata');

		// @todo: implement actual logic which will choose based on the language and workspace, when that feature is actually implemented in the TYPO3 core
		// in TYPO3 6.2 there's only one meta data record for each file
		$statement = '
			# @tables_used=sys_file,sys_file_reference,sys_file_metadata;

			SELECT
				sys_file.*,
				IFNULL(sys_file_reference.title, sys_file_metadata.title) as ref_title,
				IFNULL(sys_file_reference.description, sys_file_metadata.description) as ref_description,
				IFNULL(sys_file_reference.alternative, sys_file_metadata.alternative) as ref_alternative,
				sys_file_reference.link as ref_link,
				sys_file_reference.downloadname as ref_downloadname
			FROM
				sys_file
				INNER JOIN sys_file_metadata ON (sys_file.uid = sys_file_metadata.file ' . $metaDataEnableFields . ')
				INNER JOIN sys_file_reference ON (
					sys_file.uid = sys_file_reference.uid_local AND
					sys_file_reference.tablenames = ' . $this->getDatabase()->fullQuoteStr($tableName, 'sys_file_reference') . ' AND
					sys_file_reference.fieldname = ' . $this->getDatabase()->fullQuoteStr($fieldName, 'sys_file_reference') . ' AND
					sys_file_reference.table_local = \'sys_file\')
			WHERE sys_file_reference.uid_foreign = ' . intval($foreignUid) . '
				' . $sysFileReferenceEnableFields . '
				' . $sysFileEnableFields . '
			ORDER BY sorting_foreign ASC';

		$query->statement($statement);

		return $query->execute();
	}

	/**
	 * @param $categoryUid
	 * @param int $offset
	 * @param int $limit
	 * @param string $orderBy
	 * @param bool $ascendingOrder
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findFilesByCategoryUids($categoryUid, $offset = 0, $limit = 0, $orderBy = 'starttime', $ascendingOrder = FALSE) {
		$queryLimit = '';
		$queryOrder = '';
		$query = $this->createQuery();
		if (intval($limit) > 0) {
			$queryLimit = ' LIMIT ' . intval($offset) . ' , ' . intval($limit);
		}
		if (strlen($orderBy)) {
			$queryOrder = ' ORDER BY ' . $orderBy;
			$queryOrder .= $ascendingOrder ? ' ASC' : ' DESC';
		}

		$statement = '
			# @tables_used=sys_category,sys_category_record_mm,sys_file;

			SELECT DISTINCT sys_file.*
			FROM sys_category,sys_category_record_mm,sys_file
			WHERE sys_category.uid=sys_category_record_mm.uid_local
			AND sys_file.uid=sys_category_record_mm.uid_foreign
			AND sys_category.uid in (' . implode(',', $categoryUid) . ')
			AND sys_category_record_mm.tablenames = \'sys_file\'' . $queryOrder . $queryLimit;

		$query->statement($statement);

		return $query->execute();
	}
}

