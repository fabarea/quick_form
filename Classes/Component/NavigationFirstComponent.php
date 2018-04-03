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

/**
 * A navigation first component to be rendered in a Quick Form.
 */
class NavigationFirstComponent extends GenericComponent
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $partialName = 'Form/NavigationFirst';
        $arguments = array();
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
