<?php

namespace Vanilla\QuickForm\Component;

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

/**
 * A multi-choices form component to be rendered in a Quick Form.
 */
class MultiChoicesComponent extends GenericComponent
{

    /**
     * Constructor
     *
     * @param string $property
     * @param string $label
     * @param array $options for the Partials, array('key' => 'value')
     *              + "class" give a class name, optional: checkbox-inline, default: checkbox
     */
    public function __construct($property, $label = '', array $options = array())
    {
        $partialName = 'Form/MultiChoices';
        $arguments = $options;
        $arguments['property'] = $property;

        if (empty($label)) {
            $label = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
        }
        $arguments['label'] = $label;
        parent::__construct($partialName, $arguments);
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return string
     */
    public function getPartialName()
    {
        return $this->partialName;
    }

}
