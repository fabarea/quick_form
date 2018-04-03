<?php

namespace Vanilla\QuickForm\Validator;

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

use Cobweb\BobstForms\Domain\Model\Request;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use Vanilla\QuickForm\Validation\ValidationService;
use Vanilla\QuickForm\Validation\ValidationStrategy;

/**
 * Generic Entity Object validator
 */
class EntityObjectValidator extends AbstractValidator
{

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * @var \TYPO3\CMS\Extbase\Service\TypoScriptService
     * @inject
     */
    protected $typoScriptService;

    /**
     * Custom validation of the given Request according to its type.
     *
     * @param Request $request
     * @return void
     */
    public function isValid($request)
    {

        $validationService = $this->getValidationService($request);

        foreach ($this->getFields($request) as $fieldName => $configuration) {

            $propertyName = GeneralUtility::underscoredToLowerCamelCase($fieldName);
            $value = $this->getValue($request, $propertyName);

            if (!$validationService->isValid($propertyName, $value)) {
                $message = $validationService->getErrorMessages($propertyName, $value);
                $this->addError($message, 1384172361);
            };
        }
    }

    /**
     * Returns the fields.
     *
     * @param \Cobweb\BobstForms\Domain\Model\Request $request
     * @param string $propertyName
     * @return mixed
     */
    protected function getValue(Request $request, $propertyName)
    {

        $fieldNamePrefix = 'tx_bobstforms_pi1';
        $formObjectName = 'request';

        // Check whether the property contains an uploaded file
        // @todo refactor me, quick implementation. Could be an UploadedFile object -> normalized value.
        if (isset($_FILES[$fieldNamePrefix]['name'][$formObjectName][$propertyName])) {
            $value = array(
                'name' => $_FILES[$fieldNamePrefix]['name'][$formObjectName][$propertyName],
                'type' => $_FILES[$fieldNamePrefix]['type'][$formObjectName][$propertyName],
                'tmp_name' => $_FILES[$fieldNamePrefix]['tmp_name'][$formObjectName][$propertyName],
                'error' => $_FILES[$fieldNamePrefix]['error'][$formObjectName][$propertyName],
                'size' => $_FILES[$fieldNamePrefix]['size'][$formObjectName][$propertyName],
            );
        } else {
            $getter = 'get' . ucfirst($propertyName);
            $value = $request->$getter();
        }

        return $value;
    }

    /**
     * Returns the fields.
     *
     * @param \Cobweb\BobstForms\Domain\Model\Request $request
     * @return array
     */
    protected function getFields(Request $request)
    {
        $validationConfiguration = $this->getValidationConfiguration();
        $fields = $validationConfiguration[$request->getType()];
        return $fields;
    }

    /**
     * Returns the validation configuration
     *
     * @return string
     */
    protected function getValidationConfiguration()
    {
        $configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $validationConfiguration = $this->typoScriptService->convertTypoScriptArrayToPlainArray($configuration['plugin.']['tx_quickform.']['validate.']);
        return $validationConfiguration['request'];
    }

    /**
     * Return the validation object
     *
     * @param \Cobweb\BobstForms\Domain\Model\Request $request
     * @return \Vanilla\QuickForm\Validation\ValidationService
     */
    protected function getValidationService(Request $request)
    {

        /** @var \Vanilla\QuickForm\Validation\ValidationServiceConfigurator $serviceConfigurator */
        $serviceConfigurator = $this->getObjectManager()->get('Vanilla\QuickForm\Validation\ValidationServiceConfigurator');

        $serviceConfigurator->set('objectName', 'request');
        $serviceConfigurator->set('validationType', ValidationStrategy::TYPOSCRIPT);
        $serviceConfigurator->set('type', $request->getType());

        return ValidationService::getInstance($serviceConfigurator);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected function getObjectManager()
    {
        return GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
    }
}
