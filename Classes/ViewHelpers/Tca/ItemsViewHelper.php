<?php
namespace Vanilla\QuickForm\ViewHelpers\Tca;

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
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which returns options given a field name.
 */
class ItemsViewHelper extends RenderViewHelper {

	/**
	 * Returns options of the current property.
	 * - $items can be either an array which is then returned straightaway or
	 *   it can also be a string which corresponds to a Fluid variable given in the Controller.
	 *   If it is a string, argument $itemsDataType is required and corresponds to a table name
	 *
	 * @param mixed $items can be a string which corresponds to a fluid variable item.
	 * @param string $itemsDataType
	 * @param boolean $removeEmptyValues
	 * @return array
	 */
	public function render($items = NULL, $itemsDataType = '', $removeEmptyValues = FALSE) {

		if (is_array($items)) {
			return $items;
		} elseif(is_string($items)) {
			$items = $this->fetchItemsFromFluidVariable($items, $itemsDataType);
		} else {
			$items = $this->fetchItemsFromTca($removeEmptyValues);

		}
		return $items;
	}

	/**
	 * Retrieve items from TCA.
	 *
	 * @param boolean $removeEmptyValues
	 * @throws \Exception
	 * @return array
	 */
	protected function fetchItemsFromTca($removeEmptyValues) {

		$dataType = $this->templateVariableContainer->get('dataType');
		if (empty($dataType)) {
			throw new \Exception('Could not found a valid data type', 1385408252);
		}

		$property = $this->templateVariableContainer->get('property');
		if (empty($property)) {
			throw new \Exception('Could not found a valid property', 1385588086);
		}

		$fieldName = GeneralUtility::camelCaseToLowerCaseUnderscored($property);

		$values = $this->fetchItems($dataType, $fieldName, $removeEmptyValues);
		$itemsFromDatabase = $this->fetchItemsFromDatabase($dataType, $fieldName);

		if (!empty($itemsFromDatabase)) {
			$values = $values + $itemsFromDatabase;
		}

		$itemsFromUserFunction = $this->fetchItemsFromUserFunction($dataType, $fieldName);
		if (!empty($itemsFromUserFunction)) {
			$values = $values + $itemsFromUserFunction;
		}

		return $values;
	}

	/**
	 * Retrieve items from TCA.
	 *
	 * @param string $items
	 * @param string $itemsDataType
	 * @throws \Exception
	 * @return array
	 */
	protected function fetchItemsFromFluidVariable($items, $itemsDataType) {

		if ($itemsDataType === '') {
			throw new \Exception('Attribute $itemDataType can not be empty', 1389936621);
		}

		if (!$this->templateVariableContainer->exists($items)) {
			$message = sprintf('I could not fetch items for Fluid variable "%s". Has it been set in the Controller?', $items);
			throw new \Exception($message, 1389880458);
		}

		$labelField = Tca::table($itemsDataType)->getLabelField();
		$labelProperty = GeneralUtility::underscoredToLowerCamelCase($labelField);

		$values = array();
		foreach ($this->templateVariableContainer->get($items) as $subject) {
			$key = ObjectAccess::getProperty($subject, 'uid');
			$value = ObjectAccess::getProperty($subject, is_array($subject) ? $labelField : $labelProperty);
			$values[$key] = $value;
		}
		return $values;
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
		$values = array();

		$configuration = Tca::table($tableName)->field($fieldName)->getConfiguration();
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
	 * Retrieve items from User Function.
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @return array
	 */
	protected function fetchItemsFromUserFunction($tableName, $fieldName) {
		$values = array();

		$configuration = Tca::table($tableName)->field($fieldName)->getConfiguration();
		if (!empty($configuration['itemsProcFunc'])) {
			$parts = explode('php:', $configuration['itemsProcFunc']);
			if (!empty($parts[1])) {

				list($class, $method) = explode('->', $parts[1]);

				$parameters['items'] = array();
				$object = GeneralUtility::makeInstance($class);
				$object->$method($parameters);

				foreach ($parameters['items'] as $items) {
					$values[$items[1]] = $items[0];
				}
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

		$foreignTable = Tca::table($tableName)->field($fieldName)->getForeignTable();
		if (!empty($foreignTable)) {

			$foreignLabelField = Tca::table($foreignTable)->getLabelField();
			$foreignOrder = Tca::table($tableName)->field($fieldName)->getForeignOrder();

			$clause = '1 = 1 ';
			$clause .= Tca::table($tableName)->field($fieldName)->getForeignClause();
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
