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
 * A generic form Component to be rendered in a Quick Form.
 */
class GenericComponent implements ComponentInterface
{

    /**
     * @var string
     */
    protected $partialName;

    /**
     * @var string
     */
    protected $partialExtensionKey;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * Constructor a Generic Component in Quick Form.
     *
     * @param string $partialName
     * @param array $arguments
     * @param string $partialExtensionKey
     */
    public function __construct($partialName, $arguments = array(), $partialExtensionKey = '')
    {
        $this->partialName = $partialName;
        $this->arguments = $arguments;
        if (!empty($partialExtensionKey)) {
            $this->partialExtensionKey = $partialExtensionKey;
        }
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

    /**
     * @return string
     */
    public function getPartialExtensionKey()
    {
        return $this->partialExtensionKey;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'partial' => $this->getPartialName(),
            'arguments' => $this->getArguments(),
            'partialExtensionKey' => $this->getPartialExtensionKey(),
        );
    }

    /**
     * Magic method implementation for retrieving state.
     *
     * @param array $states
     * @return GenericComponent
     */
    static public function __set_state($states)
    {
        return new GenericComponent($states['partialName'], $states['arguments'], $states['partialExtensionKey']);
    }

}
