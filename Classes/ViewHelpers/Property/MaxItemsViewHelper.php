<?php
namespace TYPO3\CMS\QuickForm\ViewHelpers\Property;
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
use TYPO3\CMS\QuickForm\ViewHelpers\AbstractValidationViewHelper;
use TYPO3\CMS\Vidi\Tca\TcaService;

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
		$configuration = TcaService::table($dataType)->field($fieldName)->getConfiguration();

		if (isset($configuration['maxitems'])) {
			$result = $configuration['maxitems'];
		}
		return $result;
	}
}

?>