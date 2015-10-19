<?php
namespace Vanilla\QuickForm;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer;
use TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer;

/**
 * A class to store internal instances of Quick Form to speed up
 */
class ObjectFactory implements SingletonInterface {

	/**
	 * @var $instances
	 */
	protected $instances = array();

	/**
	 * Gets a singleton instance of this class.
	 *
	 * @return \Vanilla\QuickForm\ObjectFactory
	 */
	static public function getInstance() {
		return GeneralUtility::makeInstance('Vanilla\QuickForm\ObjectFactory');
	}

	/**
	 * Forge a Render View Helper object
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext
	 * @param \TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer $templateVariableContainer
	 * @param \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
	 * @return \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
	 */
	public function getRenderViewHelper(ControllerContext $controllerContext,
	                                    TemplateVariableContainer $templateVariableContainer,
	                                    ViewHelperVariableContainer $viewHelperVariableContainer) {

		if (empty($this->instances['renderViewHelper'])) {

			/** @var \TYPO3\CMS\Fluid\Core\Rendering\RenderingContext $renderingContext */
			$renderingContext = $this->getObjectManager()->get('TYPO3\CMS\Fluid\Core\Rendering\RenderingContext');
			$renderingContext->setControllerContext($controllerContext);
			$renderingContext->injectTemplateVariableContainer($templateVariableContainer);

			// Inject Variable Container
			$propertyReflection = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Reflection\PropertyReflection', $renderingContext, 'viewHelperVariableContainer');
			$propertyReflection->setAccessible(TRUE);
			$propertyReflection->setValue($renderingContext, $viewHelperVariableContainer);

			/** @var \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper $renderViewHelper */
			$renderViewHelper = $this->getObjectManager()->get('TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper');
			$renderViewHelper->setRenderingContext($renderingContext);

			$this->instances['renderViewHelper'] = $renderViewHelper;
		}
		return $this->instances['renderViewHelper'];
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected function getObjectManager() {
		return GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
	}

}
