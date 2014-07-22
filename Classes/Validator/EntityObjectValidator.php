<?php
namespace Vanilla\QuickForm\Validator;

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
use Cobweb\BobstForms\Domain\Model\Request;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use Vanilla\QuickForm\Validation\ValidationService;
use Vanilla\QuickForm\Validation\ValidationStrategy;

/**
 * Generic Entity Object validator
 */
class EntityObjectValidator extends AbstractValidator {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\TypoScriptService
	 * @inject
	 */
	protected $typoScriptService;

	/**
	 * Custom validation of the given Request according to its type.
	 *
	 * @param Request $request
	 * @return void
	 */
	public function isValid($request) {

		$validationService = $this->getValidationService($request);

		foreach ($this->getFields($request) as $fieldName => $configuration) {

			$propertyName = GeneralUtility::underscoredToLowerCamelCase($fieldName);
			$value = $this->getValue($request, $propertyName);

			if (!$validationService->isValid($propertyName, $value)) {
				$message = $validationService->getErrorMessages($propertyName, $value);
				$this->addError($message, 1384172361);
			};
		}
	}

	/**
	 * Returns the fields.
	 *
	 * @param \Cobweb\BobstForms\Domain\Model\Request $request
	 * @param string $propertyName
	 * @return mixed
	 */
	protected function getValue(Request $request, $propertyName) {

		$fieldNamePrefix = 'tx_bobstforms_pi1';
		$formObjectName = 'request';

		// Check whether the property contains an uploaded file
		// @todo refactor me, quick implementation. Could be an UploadedFile object -> normalized value.
		if (isset($_FILES[$fieldNamePrefix]['name'][$formObjectName][$propertyName])) {
			$value = array(
				'name' => $_FILES[$fieldNamePrefix]['name'][$formObjectName][$propertyName],
				'type' => $_FILES[$fieldNamePrefix]['type'][$formObjectName][$propertyName],
				'tmp_name' => $_FILES[$fieldNamePrefix]['tmp_name'][$formObjectName][$propertyName],
				'error' => $_FILES[$fieldNamePrefix]['error'][$formObjectName][$propertyName],
				'size' => $_FILES[$fieldNamePrefix]['size'][$formObjectName][$propertyName],
			);
		} else {
			$getter = 'get' . ucfirst($propertyName);
			$value = $request->$getter();
		}

		return $value;
	}

	/**
	 * Returns the fields.
	 *
	 * @param \Cobweb\BobstForms\Domain\Model\Request $request
	 * @return array
	 */
	protected function getFields(Request $request) {
		$validationConfiguration = $this->getValidationConfiguration();
		$fields = $validationConfiguration[$request->getType()];
		return $fields;
	}

	/**
	 * Returns the validation configuration
	 *
	 * @return string
	 */
	protected function getValidationConfiguration() {
		$configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$validationConfiguration = $this->typoScriptService->convertTypoScriptArrayToPlainArray($configuration['plugin.']['tx_quickform.']['validate.']);
		return $validationConfiguration['request'];
	}

	/**
	 * Return the validation object
	 *
	 * @param \Cobweb\BobstForms\Domain\Model\Request $request
	 * @return \Vanilla\QuickForm\Validation\ValidationService
	 */
	protected function getValidationService(Request $request) {

		/** @var \Vanilla\QuickForm\Validation\ValidationServiceConfigurator $serviceConfigurator */
		$serviceConfigurator = $this->getObjectManager()->get('Vanilla\QuickForm\Validation\ValidationServiceConfigurator');

		$serviceConfigurator->set('objectName', 'request');
		$serviceConfigurator->set('validationType', ValidationStrategy::TYPOSCRIPT);
		$serviceConfigurator->set('type', $request->getType());

		return ValidationService::getInstance($serviceConfigurator);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected function getObjectManager() {
		return GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
	}
}
