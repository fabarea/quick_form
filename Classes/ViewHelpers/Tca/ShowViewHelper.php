<?php

namespace Vanilla\QuickForm\ViewHelpers\Tca;

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
 * View helper which render a detail view of a TCA data type.
 */
class ShowViewHelper extends FormViewHelper
{

    /**
     * Computes a partial name for an item.
     *
     * @param array $item
     * @return array
     */
    protected function computePartialNameForItem($item)
    {
        $partial = $item['partial'];
        $searches = array('Form/');
        $replaces = array('Show/');
        return str_replace($searches, $replaces, $partial);
    }

    /**
     * Computes a partial name for a field.
     *
     * @param string $section
     * @return string
     */
    protected function computePartialNameForField($section)
    {
        return 'Show/' . $section;
    }

}
