<?php
namespace Vanilla\QuickForm\ViewHelpers\Format;

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

/**
 * View helper which format a date. Useful in the context of an email.
 */
class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns a date given a format.
	 *
	 * @param string $format
	 * @return string
	 */
	public function render($format = 'Y-m-d') {
		$content = $this->renderChildren();
		$date = new \DateTime();
		$date->setTimestamp($content);
		return $date->format($format);
	}
}
