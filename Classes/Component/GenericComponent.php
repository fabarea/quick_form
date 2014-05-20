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

/**
 * A generic form Component to be rendered in a Quick Form.
 */
class GenericComponent implements ComponentInterface {

	/**
	 * @var string
	 */
	protected $partialName;

	/**
	 * @var string
	 */
	protected $partialExtensionKey;

	/**
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * Constructor a Generic Component in Quick Form.
	 *
	 * @param string $partialName
	 * @param array $arguments
	 * @param string $partialExtensionKey
	 */
	public function __construct($partialName, $arguments = array(), $partialExtensionKey = '') {
		$this->partialName = $partialName;
		$this->arguments = $arguments;
		if (!empty($partialExtensionKey)) {
			$this->partialExtensionKey = $partialExtensionKey;
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
	public function getPartialExtensionKey() {
		return $this->partialExtensionKey;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array(
			'partial' => $this->getPartialName(),
			'arguments' => $this->getArguments(),
			'partialExtensionKey' => $this->getPartialExtensionKey(),
		);
	}

	/**
	 * Magic method implementation for retrieving state.
	 *
	 * @param array $states
	 * @return GenericComponent
	 */
	static public function __set_state($states) {
		return new GenericComponent($states['partialName'], $states['arguments'], $states['partialExtensionKey']);
	}
}
