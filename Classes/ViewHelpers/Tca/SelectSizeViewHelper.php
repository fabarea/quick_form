<?php
namespace Vanilla\QuickForm\ViewHelpers\Tca;

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
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Vanilla\QuickForm\ViewHelpers\RenderViewHelper;
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
