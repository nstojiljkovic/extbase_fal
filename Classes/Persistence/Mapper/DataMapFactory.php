<?php
namespace EssentialDots\ExtbaseFal\Persistence\Mapper;

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
 * Class DataMapFactory
 *
 * @package EssentialDots\ExtbaseFal\Persistence\Mapper
 * @author Nikola Stojiljkovic
 */
class DataMapFactory extends \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory implements \EssentialDots\ExtbaseDomainDecorator\Persistence\Mapper\DataMapFactoryInterface {

	/**
	 * Builds a data map by adding column maps for all the configured columns in the $TCA.
	 * It also resolves the type of values the column is holding and the typo of relation the column
	 * represents.
	 *
	 * @param string $className The class name you want to fetch the Data Map for
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMap
	 * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidClassException
	 */
	public function buildDataMap($className) {
		return parent::buildDataMap($className);
	}
}