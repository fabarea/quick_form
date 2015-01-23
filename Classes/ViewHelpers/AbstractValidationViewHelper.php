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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vanilla\QuickForm\Validation\ValidationService;

/**
 * Abstract View helper which enables to get some protected attributes.
 */
abstract class AbstractValidationViewHelper extends AbstractViewHelper {

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

	/**
	 * Tell whether the form is being posted.
	 *
	 * @return bool
	 */
	protected function isFormPosted() {

		$fieldNamePrefix = (string) $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'fieldNamePrefix');
		$formObjectName = (string) $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObjectName');
		$arguments = GeneralUtility::_GP($fieldNamePrefix);
		return !empty($arguments[$formObjectName]);
	}

	/**
	 * Return the current value for this property.
	 *
	 * @param string $property
	 * @return mixed
	 */
	protected function getValue($property) {
		$value = '';

		$fieldNamePrefix = (string) $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'fieldNamePrefix');
		$formObjectName = (string) $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObjectName');

		// Check whether the property contains an uploaded file
		// @todo refactor me, quick implementation. Could be an UploadedFile object -> normalized value.
		if (isset($_FILES[$fieldNamePrefix]['name'][$formObjectName][$property])) {
			$value = array(
				'name' => $_FILES[$fieldNamePrefix]['name'][$formObjectName][$property],
				'type' => $_FILES[$fieldNamePrefix]['type'][$formObjectName][$property],
				'tmp_name' => $_FILES[$fieldNamePrefix]['tmp_name'][$formObjectName][$property],
				'error' => $_FILES[$fieldNamePrefix]['error'][$formObjectName][$property],
				'size' => $_FILES[$fieldNamePrefix]['size'][$formObjectName][$property],
			);
		} else {

			// Otherwise get from the GP.
			$arguments = GeneralUtility::_GP($fieldNamePrefix);

			if (isset($arguments[$formObjectName][$property])) {
				$value = $arguments[$formObjectName][$property];
			} elseif($this->templateVariableContainer->exists($formObjectName)) {
				$object = $this->templateVariableContainer->get($formObjectName);
				if (is_object($object)) {
					$value = ObjectAccess::getProperty($object, $property);
				}
			}
		}
		return $value;
	}

	/**
	 * Return the validation object
	 *
	 * @return \Vanilla\QuickForm\Validation\ValidationService
	 */
	protected function getValidationService() {

		// @todo save Service Configurator, singleton? How to persist it?

		/** @var \Vanilla\QuickForm\Validation\ValidationServiceConfigurator $serviceConfigurator */
		$serviceConfigurator = $this->objectManager->get('Vanilla\QuickForm\Validation\ValidationServiceConfigurator');

		$objectName = $this->getViewHelperVariableContainer()->get('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		$serviceConfigurator->set('objectName', $objectName);

		$validationType = $this->getTemplateVariableContainer()->get('validationType');
		$serviceConfigurator->set('validationType', $validationType);

		$type = $this->getTemplateVariableContainer()->get('type');
		$serviceConfigurator->set('type', $type);

		return ValidationService::getInstance($serviceConfigurator);
	}

}
