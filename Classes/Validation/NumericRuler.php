<?php

namespace Vanilla\QuickForm\Validation;

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
use Fab\Vidi\Converter\Property;
use Fab\Vidi\Tca\Tca;

/**
 * Tell the rule for a "Numeric" validation.
 */
class NumericRuler extends AbstractRuler
{

    /**
     * Tell whether the property should be validated as numeric relying on the TCA strategy.
     *
     * @param string $property
     * @return bool
     */
    protected function getRuleWithTcaStrategy($property)
    {
        $dataType = $this->configuration['dataType'];
        $fieldName = Property::name($property)->of($dataType)->toFieldName();
        return Tca::table($dataType)->field($fieldName)->getType() === FieldType::NUMBER;
    }

    /**
     * Tell whether the property should be validated as numeric relying on the TypoScript strategy.
     *
     * @param string $property
     * @throws \Exception
     * @return bool
     */
    protected function getRuleWithTypoScriptStrategy($property)
    {
        return parent::getRuleWithTypoScriptStrategy($property, ValidatorName::NUMERIC);
    }

    /**
     * Tell whether the property should be validated as numeric relying on the Object strategy.
     *
     * @param string $property
     * @return bool
     */
    protected function getRuleWithObjectStrategy($property)
    {
        return parent::getRuleWithObjectStrategy($property, ValidatorName::NUMERIC);
    }

}
