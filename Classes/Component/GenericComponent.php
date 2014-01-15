<?php
namespace TYPO3\CMS\QuickForm\Component;

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

/**
 * A generic form Component to be rendered in a Quick Form.
 */
class GenericComponent implements ComponentInterface {

	/**
	 * Extension key where to find the default Partials
	 */
	const DEFAULT_EXTENSION_KEY = 'quick_form';

	/**
	 * @var string
	 */
	protected $partialName;

	/**
	 * @var string
	 */
	protected $extensionKey = self::DEFAULT_EXTENSION_KEY;

	/**
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * constructor
	 */
	public function __construct($partialName, $arguments = array(), $extensionKey = '') {
		$this->partialName = $partialName;
		$this->arguments = $arguments;
		if (!empty($extensionKey)) {
			$this->extensionKey = $extensionKey;
		}
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

	/**
	 * @return string
	 */
	public function getExtensionKey() {
		return $this->extensionKey;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array(
			'partial' => $this->getPartialName(),
			'arguments' => $this->getArguments(),
			'extensionKey' => $this->getExtensionKey(),
		);
	}

	/**
	 * Magic method implementation for retrieving state.
	 *
	 * @param array $states
	 * @return GenericComponent
	 */
	static public function __set_state($states) {
		$extensionKey = empty($states['extensionKey']) ? GenericComponent::DEFAULT_EXTENSION_KEY : $states['extensionKey'];
		return new GenericComponent($states['partialName'], $states['arguments'], $extensionKey);
	}
}

?>