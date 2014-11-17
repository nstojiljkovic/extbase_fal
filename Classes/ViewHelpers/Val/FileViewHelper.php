<?php
namespace EssentialDots\ExtbaseFal\ViewHelpers\Val;

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
 * Class FileViewHelper
 *
 * @package EssentialDots\ExtbaseFal\ViewHelpers\Val
 * @author Nikola Stojiljkovic
 * @author Marko Ciric
 */
class FileViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper implements \TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface {

	/**
	 * Initialize all arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('as', 'string', 'Variable name to insert result into, suppresses output');
	}

	/**
	 * @param string $fileUid
	 * @return object|null
	 */
	public function render($fileUid) {
		return self::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
	}

	/**
	 * @param array $arguments
	 * @param callable $renderChildrenClosure
	 * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return mixed|null|object|string
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileException
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 */
	static public function renderStatic(array $arguments, \Closure $renderChildrenClosure, \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		$result = NULL;

		/** @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManagerInterface */
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		/** @var $fileRepository \EssentialDots\ExtbaseFal\Domain\Repository\FileRepository */
		$fileRepository = $objectManager->get('EssentialDots\\ExtbaseFal\\Domain\\Repository\\FileRepository');
		$value = $fileRepository->findByUid($arguments['fileUid']);

		if ($arguments['as']) {
			$variableNameArr = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('.', $arguments['as'], TRUE, 2);

			$variableName = $variableNameArr[0];
			$attributePath = $variableNameArr[1];

			if ($renderingContext->getTemplateVariableContainer()->exists($variableName)) {
				$oldValue = $renderingContext->getTemplateVariableContainer()->get($variableName);
				$renderingContext->getTemplateVariableContainer()->remove($variableName);
			}
			if ($attributePath) {
				if ($oldValue && is_array($oldValue)) {
					$templateValue = $oldValue;
					$templateValue[$attributePath] = $value;
				} else {
					$templateValue = array(
						$attributePath => $value
					);
				}
			} else {
				$templateValue = $value;
			}
			$renderingContext->getTemplateVariableContainer()->add($variableName, $templateValue);
		} else {
			$result = $value;
		}

		return $result;
	}
}
