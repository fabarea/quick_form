<?php
namespace Vanilla\QuickForm\Component;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Fabien Udriot <fabien.udriot@typo3.org>
 *
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
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
