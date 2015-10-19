<?php
namespace Vanilla\QuickForm\ViewHelpers\Form;

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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which returns an input name according to a property.
 * Useful for the MultiChoices partial.
 */
class InputNameViewHelper extends AbstractViewHelper {

	/**
	 * Returns an input name according to a property.
	 *
	 * @return string
	 */
	public function render() {
		$formObjectName = (string)$this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObjectName');
		$property = $this->templateVariableContainer->get('property');

		// Use name of type array for the purpose of multi-choice.
		return sprintf('%s[%s][]', $formObjectName, $property);
	}

}
