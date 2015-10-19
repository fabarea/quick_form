<?php
namespace Vanilla\QuickForm\ViewHelpers\Math;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper which tells whether a property is required given a property name.
 */
class DivisionViewHelper extends AbstractViewHelper {

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return mixed
	 */
	protected function render($a, $b) {
		return ($b <> 0 ? round($a / $b) : $a);
	}

}
