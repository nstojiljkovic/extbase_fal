<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\DataHandling\\DataHandler']['className'] = 'EssentialDots\\ExtbaseFal\\DataHandling\\DataHandler';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['t3lib_TCEmain']['className'] = 'EssentialDots\\ExtbaseFal\\DataHandling\\DataHandler';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Resource\\Service\\FileProcessingService']['className'] = 'EssentialDots\\ExtbaseFal\\Resource\\Service\\FileProcessingService';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Resource\\Index\\Indexer']['className'] = 'EssentialDots\\ExtbaseFal\\Resource\\Index\\Indexer';

/* @var $decoratorManager \EssentialDots\ExtbaseDomainDecorator\Decorator\DecoratorManager */
$decoratorManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance("EssentialDots\\ExtbaseDomainDecorator\\Decorator\\DecoratorManager");
$decoratorManager->registerBackendAndDataMapFactory('EssentialDots\\ExtbaseFal\\Domain\\Model\\AbstractFile', 'EssentialDots\\ExtbaseFal\\Persistence\\Generic\\Backend', 'EssentialDots\\ExtbaseFal\\Persistence\\Mapper\\DataMapFactory');
$decoratorManager->registerBackendAndDataMapFactory('EssentialDots\\ExtbaseFal\\Domain\\Model\\File', 'EssentialDots\\ExtbaseFal\\Persistence\\Generic\\Backend', 'EssentialDots\\ExtbaseFal\\Persistence\\Mapper\\DataMapFactory');
unset($decoratorManager);

$extbaseObjectContainer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('EssentialDots\\ExtbaseDomainDecorator\\Object\\Container\\Container'); // Singleton
$extbaseObjectContainer->registerImplementation('EssentialDots\\ExtbaseFal\\Domain\\Model\\AbstractFile', 'EssentialDots\\ExtbaseFal\\Domain\\Model\\File');
$extbaseObjectContainer->registerImplementation('EssentialDots\\ExtbaseFal\\Domain\\Model\\AbstractFileCollection', 'EssentialDots\\ExtbaseFal\\Domain\\Model\\FileCollection');
$extbaseObjectContainer->registerImplementation('EssentialDots\\ExtbaseFal\\Domain\\Model\\AbstractFileReference', 'EssentialDots\\ExtbaseFal\\Domain\\Model\\FileReference');
unset($extbaseObjectContainer);