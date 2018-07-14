<?php
namespace EssentialDots\ExtbaseFal\Persistence\Generic\Storage;

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

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\Statement;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Exception\SqlErrorException;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;

/**
 * A persistence backend. This backend maps objects to the relational model of the storage backend.
 * It persists all added, removed and changed objects.
 *
 * @package EssentialDots\ExtbaseFal\Persistence\Generic\Storage
 * @author Nikola Stojiljkovic
 */
class FALBackend extends \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend {

	/**
	 * Checks if a Value Object equal to the given Object exists in the data base
	 *
	 * @param \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject $object
	 * @return void
	 * @throws \Exception
	 */
	public function getUidOfAlreadyPersistedValueObject(\TYPO3\CMS\Extbase\DomainObject\AbstractValueObject $object) {
		throw new \EssentialDots\ExtbaseFal\Persistence\Generic\Storage\Exception\UnsupportedQueryException('getUidOfAlreadyPersistedValueObject not implemnted yet in FALBackend');
	}

	/**
	 * Adds a row to the storage
	 *
	 * @param string $tableName The database table name
	 * @param array $row The row to insert
	 * @param boolean $isRelation TRUE if we are currently inserting into a relation table, FALSE by default
	 * @return void
	 * @throws Exception\UnsupportedQueryException
	 */
	public function addRow($tableName, array $row, $isRelation = FALSE) {
		throw new \EssentialDots\ExtbaseFal\Persistence\Generic\Storage\Exception\UnsupportedQueryException(
			'Persisting of files has not been implemented in extbase_fal yet. Please use core functions instead.', 1242854333);
	}

	/**
	 * Updates a row in the storage
	 *
	 * @param string $tableName The database table name
	 * @param array $row The row to update
	 * @param boolean $isRelation TRUE if we are currently inserting into a relation table, FALSE by default
	 * @return void
	 * @throws Exception\UnsupportedQueryException
	 */
	public function updateRow($tableName, array $row, $isRelation = FALSE) {
		throw new \EssentialDots\ExtbaseFal\Persistence\Generic\Storage\Exception\UnsupportedQueryException(
			'Persisting of files has not been implemented in extbase_fal yet. Please use core functions instead.', 1242854333);
	}

	/**
	 * Updates a relation row in the storage
	 *
	 * @param string $tableName The database relation table name
	 * @param array $row The row to be updated
	 * @return void
	 * @throws Exception\UnsupportedQueryException
	 */
	public function updateRelationTableRow($tableName, array $row) {
		throw new \EssentialDots\ExtbaseFal\Persistence\Generic\Storage\Exception\UnsupportedQueryException(
			'Persisting of files has not been implemented in extbase_fal yet. Please use core functions instead.', 1242854333);
	}

	/**
	 * Deletes a row in the storage
	 *
	 * @param string $tableName The database table name
	 * @param array $identifier An array of identifier array('fieldname' => value). This array will be transformed to a WHERE clause
	 * @param boolean $isRelation TRUE if we are currently inserting into a relation table, FALSE by default
	 * @return void
	 * @throws Exception\UnsupportedQueryException
	 */
	public function removeRow($tableName, array $identifier, $isRelation = FALSE) {
		throw new \EssentialDots\ExtbaseFal\Persistence\Generic\Storage\Exception\UnsupportedQueryException(
			'Persisting of files has not been implemented in extbase_fal yet. Please use core functions instead.', 1242854333);
	}

	/**
	 * Fetches maximal value for given table column
	 *
	 * @param string $tableName The database table name
	 * @param array $where An array of identifier array('fieldname' => value). This array will be transformed to a WHERE clause
	 * @param string $columnName column name to get the max value from
	 * @return void
	 * @throws Exception\UnsupportedQueryException
	 */
	public function getMaxValueFromTable($tableName, array $where, $columnName) {
		throw new \EssentialDots\ExtbaseFal\Persistence\Generic\Storage\Exception\UnsupportedQueryException('FALBackend does not support getMaxValueFromTable method.', 1242814374);
	}

	/**
	 * Returns the number of tuples matching the query.
	 *
	 * @param QueryInterface $query
	 * @throws Exception\BadConstraintException
	 * @return integer The number of matching tuples
	 */
	public function getObjectCountByQuery(QueryInterface $query) {
		// @todo: check if files actually exist
		return parent::getObjectCountByQuery($query);
	}


	/**
	 * Returns the object data matching the $query.
	 *
	 * @param QueryInterface $query
	 * @return array
	 */
	public function getObjectDataByQuery(QueryInterface $query) {
		if (version_compare(TYPO3_version, '8.7.0', '>=')) {
			$statement = $query->getStatement();
			if ($statement instanceof \TYPO3\CMS\Extbase\Persistence\Generic\Qom\Statement
				&& !$statement->getStatement() instanceof \TYPO3\CMS\Core\Database\Query\QueryBuilder
			) {
				$rows = $this->getObjectDataByRawQuery($statement);
			} else {
				$queryParser = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
				if ($statement instanceof \TYPO3\CMS\Extbase\Persistence\Generic\Qom\Statement
					&& $statement->getStatement() instanceof \TYPO3\CMS\Core\Database\Query\QueryBuilder
				) {
					$queryBuilder = $statement->getStatement();
				} else {
					$queryBuilder = $queryParser->convertQueryToDoctrineQueryBuilder($query);
				}
				$selectParts = $queryBuilder->getQueryPart('select');
				if ($queryParser->isDistinctQuerySuggested() && !empty($selectParts)) {
					$selectParts[0] = 'DISTINCT ' . $selectParts[0];
					$queryBuilder->selectLiteral(...$selectParts);
				}
				if ($query->getOffset()) {
					$queryBuilder->setFirstResult($query->getOffset());
				}
				if ($query->getLimit()) {
					$queryBuilder->setMaxResults($query->getLimit());
				}
				try {
					$rows = $queryBuilder->execute()->fetchAll();
				} catch (\Doctrine\DBAL\DBALException $e) {
					throw new \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Exception\SqlErrorException($e->getPrevious()->getMessage(), 1472074485);
				}
			}
		} else {
			$statement = $query->getStatement();
			if ($statement instanceof Statement) {
				$rows = $this->getObjectDataByRawQuery($statement);
			} else {
				$rows = $this->getRowsByStatementParts($query);
			}
		}

		foreach ($rows as $row) {
			// make sure that the $row is used to generate a core file object
			//$rows[$i]['fileObject'] =
			\TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileObject($row['uid'], $row);
		}
		// no need to perform language and workspace overlay
		//$rows = $this->doLanguageAndWorkspaceOverlay($query->getSource(), $rows, $query->getQuerySettings());
		return $rows;
	}
}