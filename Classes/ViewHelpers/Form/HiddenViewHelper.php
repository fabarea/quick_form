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

use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;

/**
 * Renders a Hidden Field. If a serializable value is found such as a text or an integer value,
 * the name attribute becomes an array:
 *
 * E.g. my_plugin[objectName][value][]
 *
 * Otherwise, it is just a regular hidden field. This View Helper is useful for handling MM relations.
 */
class HiddenViewHelper extends AbstractFormFieldViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'input';

    /**
     * Initialize the arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
    }


    /**
     * Renders a Hidden Field which format the name attribute as array if a serializable value is defined.
     * Useful for MM relations.
     *
     * @return string
     */
    public function render()
    {

        $name = $this->getName();

        $this->registerFieldNameForFormTokenGeneration($name);

        $this->tag->addAttribute('type', 'hidden');

        $value = $this->getValue();
        if (!is_object($value) && !is_null($value)) {
            $this->tag->addAttribute('value', $this->getValue());
            $name .= '[]'; //makes it appear as an array
        }
        $this->tag->addAttribute('name', $name);

        return $this->tag->render();
    }

}
