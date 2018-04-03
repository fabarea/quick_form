<?php

namespace Vanilla\QuickForm\ViewHelpers\Property;

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

use Vanilla\QuickForm\Validation\ValidatorName;
use Vanilla\QuickForm\ViewHelpers\AbstractValidationViewHelper;

/**
 * View helper which tells whether a property is required given a property name.
 */
class IsRequiredViewHelper extends AbstractValidationViewHelper
{

    /**
     * Returns whether a property is required given a property name.
     *
     * @param string $property
     * @return string
     */
    public function render($property)
    {
        $appliedValidators = $this->getValidationService()->getAppliedValidators($property);
        return isset($appliedValidators[ValidatorName::NOT_EMPTY]) || $appliedValidators[ValidatorName::FILE_REQUIRED];
    }

}
