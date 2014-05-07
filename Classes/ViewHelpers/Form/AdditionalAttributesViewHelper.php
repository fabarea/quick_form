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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * View helper which returns default additional attributes for a form component.
 */
class AdditionalAttributesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns default additional attributes for a form component.
	 *
	 * @return array
	 */
	public function render() {


		// Default attributes for Firefox, can be removed as of Firefox 31
		// @see https://developer.mozilla.org/en-US/docs/Web/Security/Securing_your_site/Turning_off_form_autocompletion
		$attributes = array('autocomplete' => true);

		/** @var \Vanilla\QuickForm\Validation\ValidationService $validationService */
		$validationService = GeneralUtility::makeInstance('Vanilla\QuickForm\Validation\ValidationService');

		// check if the the property is required.
		$property = $this->templateVariableContainer->get('property');
		if ($validationService->isRequired($property)) {
			$attributes['required'] = 'required';
		}
		return $attributes;
	}
}

?>