<?php
namespace TYPO3\CMS\QuickForm\ViewHelpers\Tca;
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
use TYPO3\CMS\Vidi\Tca\TcaService;

/**
 * View helper which translates a label for field given by the context. Under the hood, it will search teh label from the TCA.
 */
class FieldLabelViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns a label for field given by the context.
	 * It will search the label value from the TCA.
	 *
	 * @param string $key
	 * @return string
	 */
	public function render($key = '') {
		$dataType = $this->templateVariableContainer->get('dataType');

		if ($key == '') {
			$key = $this->templateVariableContainer->get('label');
		}

		return TcaService::table($dataType)->field($key)->getLabel();
	}
}
?>