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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper which tells whether a checkbox should be checked according to a property name which
 * contains comma separated values.
 */
class IsCheckedViewHelper extends AbstractViewHelper
{

    /**
     * Returns whether a checkbox should be checked according to a property name which
     * contains comma separated values.
     *
     * @param string|int $expectedValue
     * @return bool
     */
    public function render($expectedValue)
    {

        $fieldNamePrefix = (string)$this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'fieldNamePrefix');
        $formObjectName = (string)$this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObjectName');
        $property = $this->templateVariableContainer->get('property');

        // Retrieve GET / POST and object from context
        $arguments = GeneralUtility::_GP($fieldNamePrefix);
        $object = $this->templateVariableContainer->get($formObjectName);

        $values = array();

        // GET / POST values have the priority
        if (is_array($arguments[$formObjectName]) && isset($arguments[$formObjectName][$property])) {
            $values = GeneralUtility::trimExplode(',', $arguments[$formObjectName][$property]);
        } elseif (is_object($object)) {

            // Retrieve value from object.
            $value = ObjectAccess::getProperty($object, $property);
            $values = GeneralUtility::trimExplode(',', $value);
        }

        return in_array($expectedValue, $values);
    }

}
