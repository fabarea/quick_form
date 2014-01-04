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

/**
 * View helper which tells whether a property is required given a property name.
 */
class IsRequiredViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Returns whether a property is required given a property name.
	 *
	 * @param string $property
	 * @return string
	 */
	public function render($property) {

		$settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);

		$field = GeneralUtility::camelCaseToLowerCaseUnderscored($property);

		// hardcoded for now...
		// @todo get me from flexform?
		$name = 'equipment';
		$equipmentType = $settings['equipmentType'];

		$isRequired = FALSE;
		if (!empty($settings['validate'][$name][$equipmentType][$field])) {
			$validations = $settings['validate'][$name][$equipmentType][$field];
			if (isset($validations['required']) && $validations['required'] == 1) {
				$isRequired = TRUE;
			}
		}
		return $isRequired;
	}



}

?>