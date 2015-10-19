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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A text field form component to be rendered in a Quick Form.
 */
class TextFieldComponent extends GenericComponent {

	/**
	 * Constructor
	 *
	 * @param string $property
	 * @param string $label
	 * @param array $additionalAttributes
	 */
	public function __construct($property, $label = '', array $additionalAttributes = array()) {
		$partialName = 'Form/TextField';
		$arguments['property'] = $property;

		if (empty($label)) {
			$label = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
		}
		$arguments['label'] = $label;
		$arguments['additionalAttributes'] = $additionalAttributes;

		parent::__construct($partialName, $arguments);
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * @return string
	 */
	public function getPartialName() {
		return $this->partialName;
	}

}
