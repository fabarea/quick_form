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

use Fab\Vidi\Tca\FieldType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which tells the input type of the property given by the context.
 */
class InputTypeViewHelper extends AbstractViewHelper
{

    /**
     * Returns the input type of the property given by the context.
     * Possible values: text, email, ... see html5 specification
     *
     * @return string
     */
    public function render()
    {

        $property = $this->templateVariableContainer->get('property');
        $dataType = $this->templateVariableContainer->get('dataType');

        $fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
        $fieldType = Tca::table($dataType)->field($fieldName)->getType();

        $inputType = 'text';
        if ($fieldType === FieldType::EMAIL) {
            $inputType = 'email';
        }
        return $inputType;
    }

}
