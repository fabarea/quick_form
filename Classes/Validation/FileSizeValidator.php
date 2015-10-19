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

/**
 * Valid a value against "FileSize".
 */
class FileSizeValidator implements ValidatorInterface {

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

		$allowedSize = (int)$rule;

		// Handle if rule corresponds to value such as "1M".
		if (preg_match('/([0-9]+)M/', $rule, $matches)) {
			$allowedSize = $matches[1] * 1024 * 1024;
		}

		return $allowedSize > $value['size'];
	}

}
