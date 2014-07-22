<?php
namespace Vanilla\QuickForm\ViewHelpers\Form;
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
use Vanilla\QuickForm\Validation\ValidatorName;
use Vanilla\QuickForm\ViewHelpers\AbstractValidationViewHelper;

/**
 * View helper which returns default additional attributes for a form component.
 */
class AdditionalAttributesViewHelper extends AbstractValidationViewHelper {

	/**
	 * Returns default additional attributes for a form component.
	 *
	 * @return array
	 */
	public function render() {

		// Default attributes for Firefox, can be removed as of Firefox 31
		// @see https://developer.mozilla.org/en-US/docs/Web/Security/Securing_your_site/Turning_off_form_autocompletion
		$attributes = array('autocomplete' => "off");

		// Check if the the property is required.
		$property = $this->templateVariableContainer->get('property');
		$appliedValidators = $this->getValidationService()->getAppliedValidators($property);

		if (isset($appliedValidators[ValidatorName::NOT_EMPTY]) || $appliedValidators[ValidatorName::FILE_REQUIRED]) {
			$attributes['required'] = 'required';
		}

		$hasAdditionalAttributes = $this->templateVariableContainer->exists('additionalAttributes');
		if ($hasAdditionalAttributes) {
			$additionalAttributes = $this->templateVariableContainer->get('additionalAttributes');
			foreach ($additionalAttributes as $attribute => $value) {
				$attributes[$attribute] = $value;
			}
		}

		return $attributes;
	}
}
