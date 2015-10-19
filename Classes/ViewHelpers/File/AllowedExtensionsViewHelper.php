<?php
namespace Vanilla\QuickForm\ViewHelpers\File;

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
use TYPO3\CMS\Media\Utility\PermissionUtility;

/**
 * Tell what are the allowed extensions according to a storage.
 */
class AllowedExtensionsViewHelper extends AbstractViewHelper {

	/**
	 * Returns the allowed extensions for a storage.
	 *
	 * @param int $storage
	 * @return string
	 */
	public function render($storage = 1) {
		$allowedExtensions = PermissionUtility::getInstance()->getAllowedExtensions($storage);
		return implode(', ', $allowedExtensions);
	}

}