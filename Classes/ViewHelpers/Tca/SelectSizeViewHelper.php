<?php
namespace Vanilla\QuickForm\ViewHelpers\Tca;

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
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which returns the select size.
 */
class SelectSizeViewHelper extends RenderViewHelper {

	/**
	 * Returns the select size.
	 *
	 * @throws \Exception
	 * @return int
	 */
	public function render() {

		$dataType = $this->templateVariableContainer->get('dataType');
		if (empty($dataType)) {
			throw new \Exception('Could not found a valid data type', 1385588074);
		}

		$property = $this->templateVariableContainer->get('property');
		if (empty($property)) {
			throw new \Exception('Could not found a valid property', 1385588085);
		}

		$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
		$configuration = Tca::table($dataType)->field($fieldName)->getConfiguration();

		$size = 1;

		if (!empty($configuration['size'])) {
			$size = (int) $configuration['size'];
		}

		return $size;
	}
}
