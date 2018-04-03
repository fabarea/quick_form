<?php

namespace Vanilla\QuickForm\ViewHelpers\Property;

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

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Vanilla\QuickForm\ViewHelpers\RenderViewHelper;

/**
 * View helper which returns a file from the property context.
 */
class FileViewHelper extends RenderViewHelper
{

    /**
     * Returns a file from the property context.
     *
     * @return string
     */
    public function render()
    {

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
                } elseif ($file instanceof \TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder) {
                    $result = $file->getOriginalResource();

                    // Special case for File Reference.
                    if ($result instanceof \TYPO3\CMS\Core\Resource\FileReference) {
                        $result = $result->getOriginalFile();
                    }
                } elseif ((int)$file > 0) {
                    $result = ResourceFactory::getInstance()->getFileObject($file);
                }
            }
        }

        return $result;
    }
}
