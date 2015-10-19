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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which translates a label for field given by the context.
 * Under the hood, it will search the label from the TCA.
 */
class FieldLabelViewHelper extends AbstractViewHelper {

	/**
	 * Returns a label for field given by the context.
	 * It will search the label value from the TCA.
	 *
	 * An alternate label can be defined by using a "LLL:" reference in the key.
	 *
	 * @param string $key Name of a TCA field or LLL: reference
	 * @return string
	 */
	public function render($key = '') {
		$dataType = $this->templateVariableContainer->get('dataType');

		if ($key == '') {
			$labelName = $this->templateVariableContainer->exists('alternative_label') ? 'alternative_label' : 'label';
			$key = $this->templateVariableContainer->get($labelName);
		}

		if (strpos($key, 'LLL:') === 0) {
			$result = $this->getFrontendObject()->sL($key);
		} else {
			$result = Tca::table($dataType)->field($key)->getLabel();
		}
		return $result;
	}

	/**
	 * Returns an instance of the Frontend object.
	 *
	 * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected function getFrontendObject() {
		return $GLOBALS['TSFE'];
	}

}
