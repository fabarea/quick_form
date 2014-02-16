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
use TYPO3\CMS\QuickForm\ViewHelpers\AbstractValidationViewHelper;
use TYPO3\CMS\Vidi\Tca\TcaService;

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
	 * @var string
	 */
	protected $validationType;

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @var array
	 */
	static protected $instances;

	const VALIDATION_TYPE_TYPOSCRIPT = 'typoscript';
	const VALIDATION_TYPE_TCA = 'tca';
	const VALIDATION_TYPE_OBJECT = 'object';

	/**
	 * Returns a class instance.
	 *
	 * @param \TYPO3\CMS\QuickForm\ViewHelpers\AbstractValidationViewHelper $viewHelper
	 * @return \TYPO3\CMS\QuickForm\Validation\ValidationService
	 */
	static public function getInstance(AbstractValidationViewHelper $viewHelper) {

		$formObjectName = $viewHelper->getViewHelperVariableContainer()->get('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		if (empty(self::$instances[$formObjectName])) {

			$validationType = $viewHelper->getTemplateVariableContainer()->get('validationType');

			/** @var \TYPO3\CMS\QuickForm\Validation\ValidationService $instance */
			$instance = GeneralUtility::makeInstance('TYPO3\CMS\QuickForm\Validation\ValidationService');
			$instance->setTemplateVariableContainer($viewHelper->getTemplateVariableContainer())
				->setConfigurationManager($viewHelper->getConfigurationManager())
				->setReflectionService($viewHelper->getReflectionService())
				->setTypoScriptService($viewHelper->getTypoScriptService())
				->setValidationType($validationType)
				->setFormObjectName($formObjectName);

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

			if ($this->validationType == self::VALIDATION_TYPE_TCA) {
				$isRequired = $this->isRequiredWithTcaStrategy($property);
			} elseif ($this->validationType == self::VALIDATION_TYPE_TYPOSCRIPT) {
				$isRequired = $this->isRequiredWithTypoScriptStrategy($property);
			} else {
				$isRequired = $this->isRequiredWithModelStrategy($property);
			}

			$this->properties[$property]['required'] = $isRequired;
		}

		return $this->properties[$property]['required'];
	}

	/**
	 * Tell whether the field is required using the typoscript strategy.
	 *
	 * @param string $property
	 * @return bool
	 */
	protected function isRequiredWithTcaStrategy($property) {
		$dataType = $type = $this->templateVariableContainer->get('dataType');
		$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
		return TcaService::table($dataType)->field($fieldName)->isRequired();
	}

	/**
	 * Tell whether the field is required using the typoscript strategy.
	 *
	 * @param string $property
	 * @throws \Exception
	 * @return bool
	 */
	protected function isRequiredWithTypoScriptStrategy($property) {

		$isRequired = FALSE;

		$validationConfiguration = $this->getValidationConfiguration();

		$type = $this->templateVariableContainer->get('type');
		$field = GeneralUtility::camelCaseToLowerCaseUnderscored($property);

		// TRUE means the validation is defined by TypoScript useful for multi-type objects.
		if (empty($validationConfiguration[$this->formObjectName][$type])) {
			$message = sprintf('I could not find TypoScript validation for type "%s". It must be added "tx_quickform.validate.%s.%s {...}"',
				$this->formObjectName,
				$this->formObjectName,
				$type
			);
			throw new \Exception($message, 1388850911);
		}

		$rules = $validationConfiguration[$this->formObjectName][$type];
		if (!empty($rules[$field]) && isset($rules[$field]['required']) && $rules[$field]['required'] == 1) {
			$isRequired = TRUE;
		}
		return $isRequired;
	}

	/**
	 * Tell whether the field is required using the typoscript strategy.
	 *
	 * @param string $property
	 * @throws \Exception
	 * @return bool
	 */
	protected function isRequiredWithModelStrategy($property) {

		$isRequired = FALSE;

		// Get the validation value from the class name by reflection.
		$values = $this->reflectionService->getPropertyTagsValues($this->validationType, $property);
		$rules = $values['validate'];
		if (is_array($rules)) {
			$isRequired = in_array('NotEmpty', $rules);
		}

		return $isRequired;
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
	 * @param mixed $configurationManager
	 * @return $this
	 */
	public function setConfigurationManager($configurationManager) {
		$this->configurationManager = $configurationManager;
		return $this;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService
	 * @return $this
	 */
	public function setReflectionService($reflectionService) {
		$this->reflectionService = $reflectionService;
		return $this;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 * @return $this
	 */
	public function setTypoScriptService($typoScriptService) {
		$this->typoScriptService = $typoScriptService;
		return $this;
	}

	/**
	 * @param mixed $templateVariableContainer
	 * @return $this
	 */
	public function setTemplateVariableContainer($templateVariableContainer) {
		$this->templateVariableContainer = $templateVariableContainer;
		return $this;
	}

	/**
	 * @param string $formObjectName
	 * @return $this
	 */
	public function setFormObjectName($formObjectName) {
		$this->formObjectName = $formObjectName;
		return $this;
	}

	/**
	 * @param string $validationType
	 * @return $this
	 */
	public function setValidationType($validationType) {
		$this->validationType = $validationType;
		return $this;
	}
}

?>