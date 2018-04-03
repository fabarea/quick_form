<?php

namespace Vanilla\QuickForm\ViewHelpers\Form;

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
 * View helper which returns default additional attributes for a form component.
 */
class AdditionalAttributesViewHelper extends AbstractValidationViewHelper
{

    /**
     * Returns default additional attributes for a form component.
     *
     * @return array
     */
    public function render()
    {

        // Default attributes for Firefox, can be removed as of Firefox 31
        // @see https://developer.mozilla.org/en-US/docs/Web/Security/Securing_your_site/Turning_off_form_autocompletion
        $attributes = array('autocomplete' => "off");

        // Check if the the property is required.
        $property = $this->templateVariableContainer->get('property');
        $appliedValidators = $this->getValidationService()->getAppliedValidators($property);

        if (isset($appliedValidators[ValidatorName::NOT_EMPTY]) || $appliedValidators[ValidatorName::FILE_REQUIRED]) {
            $attributes['required'] = 'required';
        }

        $hasAdditionalAttributes = $this->templateVariableContainer->exists('additionalAttributes');
        if ($hasAdditionalAttributes) {
            $additionalAttributes = $this->templateVariableContainer->get('additionalAttributes');
            foreach ($additionalAttributes as $attribute => $value) {
                $attributes[$attribute] = $value;
            }
        }

        return $attributes;
    }

}
