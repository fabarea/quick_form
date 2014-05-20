<?php
namespace Vanilla\QuickForm\ViewHelpers\Property;
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
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper which format a date of a given property
 */
class DateViewHelper extends AbstractViewHelper {

	/**
	 * Returns a date given a format.
	 *
	 * @param string $format
	 * @return string
	 */
	public function render($format = 'Y-m-d') {

		$result = '';

		$fieldNamePrefix = (string) $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'fieldNamePrefix');
		$formObjectName = (string) $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObjectName');
		$property = $this->templateVariableContainer->get('property');

		// Retrieve GET / POST and object from context
		$arguments = GeneralUtility::_GP($fieldNamePrefix);


		// Arguments have priority on object.
		if (is_array($arguments[$formObjectName]) && isset($arguments[$formObjectName][$property])) {
			$result = $arguments[$formObjectName][$property];
		} elseif ($this->templateVariableContainer->exists($formObjectName)) {

			$object = $this->templateVariableContainer->get($formObjectName);
			if (is_object($object)) {

				$value = ObjectAccess::getProperty($object, $property);

				if ($value instanceof \DateTime) {
					$result = $value->format($format);
				} elseif (!empty($value) && $value > 0) {
					$date = new \DateTime();
					$date->setTimestamp($value);
					$result = $date->format($format);
				}
			}
		}

		return $result;
	}
}
