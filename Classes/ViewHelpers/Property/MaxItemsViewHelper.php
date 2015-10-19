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
use Vanilla\QuickForm\ViewHelpers\AbstractValidationViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which return the max items to be in relation with the object in the context.
 * If it is not defined, return an arbitrary big number.
 */
class MaxItemsViewHelper extends AbstractValidationViewHelper {

	/**
	 * Returns the max items to be in relation with the object in the context.
	 *
	 * @throws \Exception
	 * @return int
	 */
	public function render() {
		$result = 9999; // arbitrary big number

		$dataType = $this->templateVariableContainer->get('dataType');
		if (empty($dataType)) {
			throw new \Exception('Could not found a valid data type', 1385408252);
		}

		$property = $this->templateVariableContainer->get('property');
		$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
		$configuration = Tca::table($dataType)->field($fieldName)->getConfiguration();

		if (isset($configuration['maxitems'])) {
			$result = $configuration['maxitems'];
		}
		return $result;
	}

}
