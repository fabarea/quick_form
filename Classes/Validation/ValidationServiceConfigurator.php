<?php
namespace Vanilla\QuickForm\Validation;

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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * A class for configuring the validation service
 */
class ValidationServiceConfigurator  {


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
	 * @var array
	 */
	protected $values = array();

	/**
	 * Returns configuration for the ruler object.
	 *
	 * @param string $property
	 * @return array
	 */
	public function getConfiguration($property) {
		$configuration = array();
		$validationType = $this->get('validationType');
		switch($validationType) {
			case ValidationStrategy::TCA:
				$configuration = array(
					'dataType' => $this->get('dataType'),
				);
				break;
			case ValidationStrategy::TYPOSCRIPT:
				$configuration = array(
					'type' => $this->get('type'),
					'validationConfiguration' => $this->getValidationConfiguration(),
					'formObjectName' => $this->get('objectName'),
				);
				break;
			case ValidationStrategy::OBJECT:
				$configuration = array(
					'tagValues' => $this->reflectionService->getPropertyTagsValues($this->validationType, $property),
				);
				break;
		}

		return $configuration;
	}

	/**
	 * @param string $key
	 * @throws \Exception
	 * @return mixed
	 */
	public function get($key) {
		if (!isset($this->values[$key])) {
			$message = sprintf('I could not find a value for the key "%s" in my registry', $key);
			throw new \Exception($message, 1406008575);
		}
		return $this->values[$key];
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function set($key, $value) {
		$this->values[$key] = $value;
		return $this;
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
}
