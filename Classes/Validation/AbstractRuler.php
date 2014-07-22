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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract class for the ruler. Tell what rule should apply for the validation of a value.
 */
abstract class AbstractRuler implements RulerInterface {

	/**
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * @param array $configuration
	 */
	public function __construct(array $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * Get rule for a property given a validation strategy.
	 *
	 * @param string $property
	 * @param string $validationStrategy
	 * @return mixed
	 */
	public function getRule($property, $validationStrategy) {

		if ($validationStrategy == ValidationStrategy::TCA) {
			$normalizedRule = $this->getRuleWithTcaStrategy($property);
		} elseif ($validationStrategy == ValidationStrategy::TYPOSCRIPT) {
			$normalizedRule = $this->getRuleWithTypoScriptStrategy($property);
		} else {
			$normalizedRule = $this->getRuleWithObjectStrategy($property);
		}

		return $normalizedRule;
	}

	/**
	 * Tell whether the property should be validated as not empty relying on the TCA strategy.
	 *
	 * @param string $property
	 * @return bool
	 */
	protected function getRuleWithTcaStrategy($property) {
		return FALSE; // must be implemented in the children classes.
	}

	/**
	 * Tell whether the property should be validated as not empty relying on the TypoScript strategy.
	 *
	 * @param string $property
	 * @param string $validatorName
	 * @throws \Exception
	 * @return mixed
	 */
	protected function getRuleWithTypoScriptStrategy($property, $validatorName = '') {

		$appliedRule = FALSE;

		$validationConfiguration = $this->configuration['validationConfiguration'];
		$formObjectName = $this->configuration['formObjectName'];

		$type = $this->configuration['type'];
		$field = GeneralUtility::camelCaseToLowerCaseUnderscored($property);

		// TRUE means the validation is defined by TypoScript which is useful in the context of multi-type models.
		if (empty($validationConfiguration[$formObjectName][$type])) {
			$message = sprintf('I could not §find TypoScript validation for type "%s". It must be added "tx_quickform.validate.%s.%s {...}"',
				$type,
				$formObjectName,
				$type
			);
			throw new \Exception($message, 1388850911);
		}

		$rules = $validationConfiguration[$formObjectName][$type];
		if (!empty($rules[$field]) && isset($rules[$field][$validatorName])) {
			$appliedRule = $rules[$field][$validatorName];
		}
		return $appliedRule;
	}

	/**
	 * Tell whether the property should be validated as not empty relying on the Object strategy.
	 *
	 * @param string $property
	 * @param string $validatorName
	 * @return bool
	 */
	protected function getRuleWithObjectStrategy($property, $validatorName = '') {

		$appliedRule = FALSE;

		// Get the validation value from the class name by reflection.
		$tagValues = $this->configuration['tagValues'];
		$rules = $tagValues['validate'];
		if (is_array($rules)) {
			$appliedRule = in_array($validatorName, $rules);
		}

		return $appliedRule;
	}

}
