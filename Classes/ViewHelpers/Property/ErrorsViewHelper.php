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

use Vanilla\QuickForm\ViewHelpers\AbstractValidationViewHelper;

/**
 * View helper which returns possible errors of a property.
 */
class ErrorsViewHelper extends AbstractValidationViewHelper
{

    /**
     * @var string
     */
    protected $template = '<label class="danger">%s</label> ';

    /**
     * Returns possible error of a field.
     *
     * @return bool
     */
    public function render()
    {

        $output = '';

        if ($this->isFormPosted()) {
            // Get the current property
            $property = $this->templateVariableContainer->get('property');

            // Get the current value for the property.
            $value = $this->getValue($property);

            // Instantiate the Validation service and check whether the value is valid.
            $errorMessages = array();
            if (!$this->getValidationService()->isValid($property, $value)) {
                $errorMessages = $this->getValidationService()->getErrorMessages($property, $value);
            }

            foreach ($errorMessages as $errorMessage) {
                $output .= sprintf($this->template, $errorMessage);
            }
        }

        return $output;
    }

}
