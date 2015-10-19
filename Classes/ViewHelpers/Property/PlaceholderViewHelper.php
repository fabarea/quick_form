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
 * View helper which returns a placeholder taken from the TCA.
 */
class PlaceholderViewHelper extends RenderViewHelper {

	/**
	 * Returns a placeholder taken from the TCA.
	 * The property is given by the context.
	 * @todo remove me as handled by {qf:form.additionalAttributes()}
	 *
	 * @return NULL|string
	 */
	public function render() {
		$dataType = $this->templateVariableContainer->get('dataType');
		$property = $this->templateVariableContainer->get('property');
		$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
		return Tca::table($dataType)->field($fieldName)->get('placeholder');
	}

}
