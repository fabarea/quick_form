<?php
namespace Vanilla\QuickForm\ViewHelpers\Property;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which returns a property value. The property and the object are given from the context.
 */
class ValueViewHelper extends RenderViewHelper {

	/**
	 * Returns a property value. The property and the object are given from the context.
	 *
	 * @return string
	 */
	public function render() {

		$result = '';

		// Retrieve object or array.
		$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		if ($this->templateVariableContainer->exists($formObjectName)) {

			$object = $this->templateVariableContainer->get($formObjectName);

			if (!empty($object)) {
				// Retrieve the property name.
				$property = $this->templateVariableContainer->get('property');
				$result = ObjectAccess::getProperty($object, $property);
			} else {
				// Fetch defaults from TCA
				$dataType = $this->templateVariableContainer->get('dataType');
				$property = $this->templateVariableContainer->get('property');
				$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
				$result = Tca::table($dataType)->field($fieldName)->get('default');
			}
		}

		return $result;
	}

}
