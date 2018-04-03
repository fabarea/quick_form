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
 * Valid a value against "FileRequired".
 */
class FileRequiredValidator implements ValidatorInterface
{

    /**
     * Validate the value.
     *
     * @param string $value
     * @param string $rule
     * @return bool
     */
    public function validate($value, $rule)
    {
        return is_array($value) && !empty($value);
    }

}
