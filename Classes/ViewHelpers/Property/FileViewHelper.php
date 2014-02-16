<?php
namespace TYPO3\CMS\QuickForm\ViewHelpers\Property;

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
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper;

/**
 * View helper which returns a file from the property context.
 */
class FileViewHelper extends RenderViewHelper {

	/**
	 * Returns a file from the property context.
	 *
	 * @return string
	 */
	public function render() {

		$result = NULL;

		// Retrieve object or array.
		$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		if ($this->templateVariableContainer->exists($formObjectName)) {

			$object = $this->templateVariableContainer->get($formObjectName);

			if (!empty($object)) {
				// Retrieve the property name.
				$property = $this->templateVariableContainer->get('property');
				$file = ObjectAccess::getProperty($object, $property);

				if ($file instanceof \TYPO3\CMS\Core\Resource\AbstractFile) {
					$result = $file;
				} elseif($file instanceof \TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder) {
					$result = $file->getOriginalResource();

					// Special case for File Reference.
					if ($result instanceof \TYPO3\CMS\Core\Resource\FileReference) {
						$result = $result->getOriginalFile();
					}
				} elseif ((int) $file > 0) {
					$result = ResourceFactory::getInstance()->getFileObject($file);
				}
			}
		}

		return $result;
	}
}

?>