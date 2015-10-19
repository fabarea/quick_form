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

/**
 * A to-do form component to be rendered in a Quick Form.
 */
class TodoComponent extends GenericComponent {

	/**
	 * Constructor
	 *
	 * @param string $message
	 */
	public function __construct($message = '') {
		$partialName = 'Form/Todo';
		$arguments['message'] = $message;
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
