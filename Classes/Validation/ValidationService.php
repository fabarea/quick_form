<?php
namespace TYPO3\CMS\QuickForm\Validation;

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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\QuickForm\ViewHelpers\AbstractValidationViewHelper;

/**
 * Service class related to validation. This is meant to be used internally in Quick Form.
 */
class ValidationService implements SingletonInterface {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService;

	/**
	 * @var \TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer
	 */
	protected $templateVariableContainer;

	/**
	 * @var string
	 */
	protected $formObjectName;

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @var array
	 */
	static protected $instances;

	/**
	 * Returns a class instance.
	 *
	 * @param \TYPO3\CMS\QuickForm\ViewHelpers\AbstractValidationViewHelper $viewHelper
	 * @return \TYPO3\CMS\QuickForm\Validation\ValidationService
	 */
	static public function getInstance(AbstractValidationViewHelper $viewHelper) {

		$formObjectName = $viewHelper->getViewHelperVariableContainer()->get('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		if (empty(self::$instances[$formObjectName])) {

			/** @var \TYPO3\CMS\QuickForm\Validation\ValidationService $instance */
			$instance = GeneralUtility::makeInstance('TYPO3\CMS\QuickForm\Validation\ValidationService');
			$instance->setTemplateVariableContainer($viewHelper->getTemplateVariableContainer());
			$instance->setConfigurationManager($viewHelper->getConfigurationManager());
			$instance->setReflectionService($viewHelper->getReflectionService());
			$instance->setTypoScriptService($viewHelper->getTypoScriptService());
			$instance->setFormObjectName($formObjectName);

			self::$instances[$formObjectName] = $instance;
		}

		return self::$instances[$formObjectName];
	}

	/**
	 * Returns whether a property is required given a property name.
	 *
	 * @param string $property
	 * @return string
	 */
	public function isRequired($property) {

		if (!isset($this->properties[$property]['required'])) {

			$isRequired = FALSE;

			$configuration = $this->getValidationConfiguration();

			$type = $this->templateVariableContainer->get('type');

			$field = GeneralUtility::camelCaseToLowerCaseUnderscored($property);

			// TRUE means the validation is defined by TypoScript useful for multi-type objects.
			if (!empty($configuration['validate'][$this->formObjectName][$type][$field])) {
				$validations = $configuration['validate'][$this->formObjectName][$type][$field];
				if (isset($validations['required']) && $validations['required'] == 1) {
					$isRequired = TRUE;
				}
			} else {

				// Get the validation value from the class name by reflection.
				$className = $this->getClassName();
				$values = $this->reflectionService->getPropertyTagsValues($className, $property);
				$validations = $values['validate'];
				if (is_array($validations)) {
					$isRequired = in_array('NotEmpty', $validations);
				}
			}
			$this->properties[$property]['required'] = $isRequired;
		}

		return $this->properties[$property]['required'];
	}

	/**
	 * Returns the validation configuration
	 *
	 * @return string
	 */
	protected function getValidationConfiguration() {
		$configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		return $this->typoScriptService->convertTypoScriptArrayToPlainArray($configuration['plugin.']['tx_quickform.']['validate.']);
	}

	/**
	 * Returns the class name of the current form. It uses different strategy.
	 * It firstly reads from attribute "model" whether a class name was given.
	 * If not, it try to get the object given to the form.
	 *
	 * @throws \Exception
	 * @return string
	 */
	protected function getClassName() {

		$className = $this->templateVariableContainer->get('model');

		// try to read the class name from the object
		if (empty($className)) {
			$object = $this->templateVariableContainer->get($this->formObjectName);
			$className = get_class($object);
		}

		if (empty($className)) {
			throw new \Exception('I could not guess the class name connected to this form.', 1388850910);
		}

		return $className;
	}

	/**
	 * @param mixed $configurationManager
	 */
	public function setConfigurationManager($configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService
	 */
	public function setReflectionService($reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function setTypoScriptService($typoScriptService) {
		$this->typoScriptService = $typoScriptService;
	}

	/**
	 * @param mixed $templateVariableContainer
	 */
	public function setTemplateVariableContainer($templateVariableContainer) {
		$this->templateVariableContainer = $templateVariableContainer;
	}

	/**
	 * @param string $formObjectName
	 */
	public function setFormObjectName($formObjectName) {
		$this->formObjectName = $formObjectName;
	}
}

?>