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
use Vanilla\QuickForm\Validation\ValidationService;
use Vanilla\QuickForm\ViewHelpers\AbstractValidationViewHelper;

/**
 * View helper which tells whether a property is required given a property name.
 */
class IsRequiredViewHelper extends AbstractValidationViewHelper {

	/**
	 * Returns whether a property is required given a property name.
	 *
	 * @param string $property
	 * @return string
	 */
	public function render($property) {
		return ValidationService::getInstance($this)->isRequired($property);
	}
}

?>