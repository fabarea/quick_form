<?php
namespace Vanilla\QuickForm;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Fabien Udriot <fabien.udriot@typo3.org>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
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
