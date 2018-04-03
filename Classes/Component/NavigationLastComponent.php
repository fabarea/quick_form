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
 * A navigation component to be rendered in a Quick Form.
 */
class NavigationLastComponent extends GenericComponent
{

    /**
     * Constructor
     *
     * @param integer $previous
     */
    public function __construct($previous)
    {
        $partialName = 'Form/NavigationLast';
        $arguments['previous'] = $previous;
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
