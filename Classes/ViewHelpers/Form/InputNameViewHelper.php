<?php
namespace Vanilla\QuickForm\ViewHelpers\Form;
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
