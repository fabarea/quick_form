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

/**
 * Tell the rule for an "AllowedExtensions" validation.
 */
class AllowedExtensionsRuler extends AbstractRuler
{

    /**
     * Tell whether the property should be validated as not empty relying on the TCA strategy.
     *
     * @param string $property
     * @throws \Exception
     * @return string
     */
    protected function getRuleWithTcaStrategy($property)
    {
        throw new \Exception('Implement me method getRuleWithTcaStrategy', 1406015137);
        #$dataType = $this->configuration['dataType'];
        #$fieldName = Property::name($property)->of($dataType)->toFieldName();
        #return Tca::table($dataType)->field($fieldName)->isRequired();
    }

    /**
     * Tell whether the property should be validated as not empty relying on the TypoScript strategy.
     *
     * @param string $property
     * @throws \Exception
     * @return string
     */
    protected function getRuleWithTypoScriptStrategy($property)
    {
        return parent::getRuleWithTypoScriptStrategy($property, ValidatorName::ALLOWED_EXTENSIONS);
    }

    /**
     * Tell whether the property should be validated as not empty relying on the Object strategy.
     *
     * @param string $property
     * @return string
     */
    protected function getRuleWithObjectStrategy($property)
    {
        return parent::getRuleWithObjectStrategy($property, ValidatorName::ALLOWED_EXTENSIONS);
    }

}
