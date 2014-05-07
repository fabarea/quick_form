<?php
namespace Vanilla\QuickForm\ViewHelpers;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@typo3.org>
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
 * View helper which tells whether a property is required given a property name.
 */
class AbstractValidationViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Reflection\ReflectionService
	 * @inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\TypoScriptService
	 * @inject
	 */
	protected $typoScriptService;

	/**
	 * @return \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	public function getConfigurationManager() {
		return $this->configurationManager;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Reflection\ReflectionService
	 */
	public function getReflectionService() {
		return $this->reflectionService;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	public function getTypoScriptService() {
		return $this->typoScriptService;
	}

	/**
	 * @return \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer
	 */
	public function getViewHelperVariableContainer() {
		return $this->viewHelperVariableContainer;
	}

	/**
	 * @return \TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer
	 */
	public function getTemplateVariableContainer() {
		return $this->templateVariableContainer;
	}

}

?>