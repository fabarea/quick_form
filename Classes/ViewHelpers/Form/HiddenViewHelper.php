<?php
namespace Vanilla\QuickForm\ViewHelpers\Form;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Fabien Udriot <fabien.udriot@typo3.org>
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
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;

/**
 * Renders a Hidden Field. If a serializable value is found such as a text or an integer value,
 * the name attribute becomes an array:
 *
 * E.g. my_plugin[objectName][value][]
 *
 * Otherwise, it is just a regular hidden field. This View Helper is useful for handling MM relations.
 */
class HiddenViewHelper extends AbstractFormFieldViewHelper {

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
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
	}


	/**
	 * Renders a Hidden Field which format the name attribute as array if a serializable value is defined.
	 * Useful for MM relations.
	 *
	 * @return string
	 */
	public function render() {

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
