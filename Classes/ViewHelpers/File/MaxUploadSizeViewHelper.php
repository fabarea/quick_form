<?php

namespace Vanilla\QuickForm\ViewHelpers\File;

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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Tell what is the max upload size in Mb.
 */
class MaxUploadSizeViewHelper extends AbstractViewHelper
{

    /**
     * Returns the max upload size in Mb.
     *
     * @return string
     */
    public function render()
    {
        return round(GeneralUtility::getMaxUploadFileSize() / 1024, 1);
    }

}