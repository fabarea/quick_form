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
 * A form Component interface to be rendered in a Quick Form.
 */
interface ComponentInterface
{

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return string
     */
    public function getPartialName();

    /**
     * @return string
     */
    public function getPartialExtensionKey();

    /**
     * @return string
     */
    public function toArray();

}
