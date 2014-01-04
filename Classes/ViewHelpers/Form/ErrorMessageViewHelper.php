<?php
namespace TYPO3\CMS\QuickForm\ViewHelpers\Form;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Fabien Udriot <fabien.udriot@typo3.org>, Cobweb
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * This ViewHelper displays an error message for a field
 */
class ErrorMessageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {

//	/**
//	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
//	 */
//	protected $contentObject;
//
//	/**
//	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
//	 */
//	protected $configurationManager;
//
//	/**
//	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
//	 * @return void
//	 */
//	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
//		$this->configurationManager = $configurationManager;
//		$this->contentObject = $this->configurationManager->getContentObject();
//	}

	/**
	 * Remove all other arguments, but only leave the "property" argument; as we do not want to build a tag.
	 */
	public function initializeArguments() {
//		$this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.', TRUE);
	}

	/**
	 * Render the ViewHelper.
	 */
	public function render() {
		$errors = $this->controllerContext->getRequest()->getErrors();
		#$errors = $this->getErrorsForProperty();

		var_dump($errors);
		return '';
		exit();

		/** @var \TYPO3\CMS\Extbase\Validation\Error $error */
		$separator = $message = '';
		foreach ($errors as $error) {
//			$message .= $separator . Tx_Extbase_Utility_Localization::translate('error.code.' . $error->getCode(), 'manpower_personal_request');
//			$message .= $separator . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.code.' . $error->getCode(), 'manpower_personal_request');
			$message .= $separator . 'asdf-asdf' . $error->getCode();
			$separator = ', ';
		}
		return sprintf('<span class="help-inline">%s</span>', $message);

//		$flashMessages = $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
//		var_dump($flashMessages);
	}
}
?>
