<?php

namespace Vanilla\QuickForm\ViewHelpers\Tca;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Fab\Vidi\Converter\Property;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which returns configuration of the property from the context given a key.
 */
class ConfigurationViewHelper extends AbstractViewHelper
{

    /**
     * Returns configuration of the property from the context given a key.
     *
     * @param string $key
     * @return string
     */
    public function render($key)
    {
        $dataType = $this->templateVariableContainer->get('dataType');
        $property = $this->templateVariableContainer->get('property');
        $fieldName = Property::name($property)->of($dataType)->toFieldName();
        return Tca::table($dataType)->field($fieldName)->get($key);
    }

}
