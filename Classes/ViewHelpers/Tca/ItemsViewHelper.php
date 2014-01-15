<?php
namespace TYPO3\CMS\QuickForm\ViewHelpers\Tca;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Vidi\Tca\TcaService;

/**
 * View helper which returns options given a field name.
 */
class ItemsViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper {

	/**
	 * Returns options of the current property
	 *
	 * @param boolean $removeEmptyValues
	 * @throws \Exception
	 * @return array
	 */
	public function render($removeEmptyValues = FALSE) {

		$dataType = $this->templateVariableContainer->get('dataType');
		if (empty($dataType)) {
			throw new \Exception('Could not found a valid data type', 1385408252);
		}

		$property = $this->templateVariableContainer->get('property');
		if (empty($property)) {
			throw new \Exception('Could not found a valid property', 1385588086);
		}

		$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);

		$items = $this->fetchItems($dataType, $fieldName, $removeEmptyValues);
		$itemsFromDatabase = $this->fetchItemsFromDatabase($dataType, $fieldName);

		if (!empty($itemsFromDatabase)) {
			$items = $items + $itemsFromDatabase;
		}

		return $items;
	}

	/**
	 * Retrieve items from TCA.
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @param string $removeEmptyValues
	 * @return array
	 */
	protected function fetchItems($tableName, $fieldName, $removeEmptyValues) {

		$configuration = TcaService::table($tableName)->field($fieldName)->getConfiguration();

		$values = array();
		if (!empty($configuration['items'])) {

			foreach ($configuration['items'] as $items) {
				$value = $items[1];
				$labelKey = $items[0];

				if ($value == '' && $removeEmptyValues) {
					continue;
				}

				$label = '';
				if (!empty($labelKey)) {
					$label = LocalizationUtility::translate($labelKey, '');

					// Means, it does not correspond to a translatable label.
					if (empty($label)) {
						$label = html_entity_decode($items[0]);
					}
				}
				$values[$value] = $label;
			}
		}
		return $values;
	}

	/**
	 * Retrieve items from Database.
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @return array
	 */
	protected function fetchItemsFromDatabase($tableName, $fieldName) {
		$values = array();

		$foreignTable = TcaService::table($tableName)->field($fieldName)->getForeignTable();
		if (!empty($foreignTable)) {

			$foreignLabelField = TcaService::table($foreignTable)->getLabelField();
			$foreignOrder = TcaService::table($tableName)->field($fieldName)->getForeignOrder();

			$clause = '1 = 1';
			if ($this->isFrontendMode()) {
				$clause .= $this->getPageRepository()->enableFields($foreignTable);
			}

			$rows = $this->getDatabaseConnection()->exec_SELECTgetRows('uid, ' . $foreignLabelField, $foreignTable, $clause, '', $foreignOrder);

			foreach ($rows as $row) {
				$values[$row['uid']] = $row[$foreignLabelField];
			}
		}

		return $values;
	}

	/**
	 * Returns whether the current mode is Frontend
	 *
	 * @return string
	 */
	protected function isFrontendMode() {
		return TYPO3_MODE == 'FE';
	}

	/**
	 * Returns an instance of the page repository.
	 *
	 * @return \TYPO3\CMS\Frontend\Page\PageRepository
	 */
	protected function getPageRepository() {
		return $GLOBALS['TSFE']->sys_page;
	}

	/**
	 * Returns a pointer to the database.
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}

?>