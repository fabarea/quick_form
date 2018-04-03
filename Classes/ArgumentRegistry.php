<?php

namespace Vanilla\QuickForm;

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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A class to store the arguments being passed.
 */
class ArgumentRegistry implements SingletonInterface
{

    /**
     * @var $arguments
     */
    protected $arguments = array();

    /**
     * Gets a singleton instance of this class.
     *
     * @return \Vanilla\QuickForm\ArgumentRegistry
     */
    static public function getInstance()
    {
        return GeneralUtility::makeInstance('Vanilla\QuickForm\ArgumentRegistry');
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     * @return \Vanilla\QuickForm\ArgumentRegistry
     */
    public function set($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }
}
