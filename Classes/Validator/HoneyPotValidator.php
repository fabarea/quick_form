<?php
namespace Vanilla\QuickForm\Validator;

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
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 * Validate the honey pot
 */
class HoneyPotValidator extends AbstractValidator {

	/**
	 * Checks whether:
	 *
	 * - honeypots are empty (or still have there designated values)
	 * - there is a user agent being set
	 * - there is a fe_user cookie being set
	 *
	 * if any of these is wrong it dies with a message.
	 *
	 * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $entity
	 * @return boolean
	 */
	public function isValid($entity) {

		if (GeneralUtility::_GP('name') || GeneralUtility::_GP('email') || GeneralUtility::_GP('e-mail') || GeneralUtility::_GP('phone')) {
			die('Looks strange - u sure you are not a bot?');
		}

		if (GeneralUtility::_GP('subject') !== strrev(GeneralUtility::_GP('subject2'))) {
			die('Tempered with subject and subject2 - u sure you are not a bot?');
		}

		if (GeneralUtility::getIndpEnv('HTTP_USER_AGENT') == "") {
			die('No user agent - u sure you are not a bot?');
		}

		return TRUE;
	}
}
