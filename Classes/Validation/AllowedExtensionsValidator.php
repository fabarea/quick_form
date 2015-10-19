<?php
namespace Vanilla\QuickForm\Validation;

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

use TYPO3\CMS\Media\Utility\PermissionUtility;

/**
 * Valid a value against "AllowedExtensions".
 */
class AllowedExtensionsValidator implements ValidatorInterface {

	/**
	 * Validate the value.
	 *
	 * @param string $value
	 * @param string $rule
	 * @return bool
	 */
	public function validate($value, $rule) {

		if (is_array($value) && (int)$value['error'] !== 0) {
			return TRUE; // early return as we want to validate the file extension only if the upload has succeeded.
		}

		$isValid = FALSE;
		if (preg_match('/storage:[0-9]+/', $rule)) {
			$ruleParts = explode(':', $rule);
			$storageIdentifier = $ruleParts[1];

			$allowedExtensions = PermissionUtility::getInstance()->getAllowedExtensions($storageIdentifier);
			$extensionName = pathinfo($value['name'], PATHINFO_EXTENSION);
			$isValid = in_array($extensionName, $allowedExtensions);

		}
		return $isValid;
	}

}
