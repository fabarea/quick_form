<?php
namespace TYPO3\CMS\QuickForm\ViewHelpers\Form;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * View helper which tells whether a checkbox should be checked according to a property name which
 * contains comma separated values.
 */
class IsCheckedViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var string
	 * @todo check in HasErrorViewHelper for removing me
	 */
	protected $pluginSignature = 'tx_lima_pi1';

	/**
	 * Returns whether a checkbox should be checked according to a property name which
	 * contains comma separated values.
	 *
	 * @param string|int $expectedValue
	 * @return bool
	 */
	public function render($expectedValue) {

		# Used fall-back "GeneralUtility::_GP" since the two approaches below failed.

		# Problem with getResponse()->getArguments(), it does not merge GET / POST.
		#$arguments = $this->controllerContext->getResponse()->getArguments();

		$values = array();
		$arguments = GeneralUtility::_GP($this->pluginSignature);

		$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		$property = $this->templateVariableContainer->get('property');

		return false;
		if (is_array($arguments['equipment']) && isset($arguments['equipment'][$property])) {
			$values = GeneralUtility::trimExplode(',', $arguments['equipment'][$property]);
		} elseif ($this->templateVariableContainer->get($formObjectName)) {

			// Retrieve object.
			$object = $this->templateVariableContainer->get($formObjectName);

			// Retrieve value from object.
			$getter = 'get' . ucfirst($property);
			$values = GeneralUtility::trimExplode(',', $object->$getter());
		}

		return in_array($expectedValue, $values);
	}
}

?>