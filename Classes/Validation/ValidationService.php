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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Service class related to validation. This is meant to be used internally in Quick Form.
 */
class ValidationService implements SingletonInterface
{

    /**
     * @var ValidationServiceConfigurator
     */
    protected $serviceConfigurator;

    /**
     * @var array
     */
    protected $validationMessages = array();

    /**
     * @var array
     */
    protected $appliedValidators = array();

    /**
     * @var array
     */
    static protected $instances;

    /**
     * Returns a class instance.
     *
     * @param ValidationServiceConfigurator $serviceConfigurator
     * @return \Vanilla\QuickForm\Validation\ValidationService
     */
    static public function getInstance(ValidationServiceConfigurator $serviceConfigurator)
    {

        $objectName = $serviceConfigurator->get('objectName');
        if (empty(self::$instances[$objectName])) {

            /** @var \Vanilla\QuickForm\Validation\ValidationService $instance */
            $instance = GeneralUtility::makeInstance('Vanilla\QuickForm\Validation\ValidationService', $serviceConfigurator);
            self::$instances[$objectName] = $instance;
        }

        return self::$instances[$objectName];
    }

    /**
     * Constructor
     *
     * @param ValidationServiceConfigurator $serviceConfigurator
     */
    public function __construct(ValidationServiceConfigurator $serviceConfigurator)
    {
        $this->serviceConfigurator = $serviceConfigurator;
    }

    /**
     * Validate the property given its value and compute possible error messages.
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function getErrorMessages($property, $value)
    {

        if (!isset($this->validationMessages[$property])) {
            $this->validationMessages[$property] = array();
            $errorMessages = array();

            foreach ($this->getAppliedValidators($property) as $validatorName => $rule) {

                /** @var \Vanilla\QuickForm\Validation\ValidatorInterface $validator */
                $className = sprintf('Vanilla\QuickForm\Validation\%sValidator', $validatorName);
                $validator = GeneralUtility::makeInstance($className);
                $isValid = $validator->validate($value, $rule);

                if (!$isValid) {
                    $errorMessages[$validatorName] = $this->getErrorMessage($validatorName);
                    break; // display only one error message at a time // @todo make me configurable.
                }
            };

            $this->validationMessages[$property] = $errorMessages;
        }

        return $this->validationMessages[$property];
    }

    /**
     * Returns whether a property is valid according to its value.
     *
     * @param string $property
     * @param string $value
     * @return array
     */
    public function isValid($property, $value)
    {
        $errorMessages = $this->getErrorMessages($property, $value);
        return empty($errorMessages);
    }

    /**
     * Returns the applied validators for a property.
     *
     * @param string $property
     * @return array
     */
    public function getAppliedValidators($property)
    {

        if (!isset($this->appliedValidators[$property])) {

            // Initialize the property.
            $this->appliedValidators[$property] = array();

            $rulerConfiguration = $this->serviceConfigurator->getConfiguration($property);

            /** @var \Vanilla\QuickForm\Validation\ValidatorName $availableValidators */
            $availableValidators = GeneralUtility::makeInstance('Vanilla\QuickForm\Validation\ValidatorName');

            foreach ($availableValidators->getConstants() as $validatorName) {

                $className = sprintf('Vanilla\QuickForm\Validation\%sRuler', $validatorName);

                /** @var \Vanilla\QuickForm\Validation\RulerInterface $ruler */
                $ruler = GeneralUtility::makeInstance($className, $rulerConfiguration);
                $appliedRule = $ruler->getRule($property, $this->serviceConfigurator->get('validationType'));

                if ($appliedRule) {
                    $this->appliedValidators[$property][$validatorName] = $appliedRule;
                }
            }
        }

        return $this->appliedValidators[$property];
    }

    /**
     * Returns the error message given a failed validation.
     *
     * @param string $validatorName
     * @return array
     */
    protected function getErrorMessage($validatorName)
    {
        return LocalizationUtility::translate($validatorName, 'quick_form');
    }

}
