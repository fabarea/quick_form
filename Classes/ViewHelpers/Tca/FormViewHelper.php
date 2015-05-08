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
use Fab\Vidi\Tca\FieldType;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use Vanilla\QuickForm\Component\ComponentInterface;
use Vanilla\QuickForm\ObjectFactory;
use Vanilla\QuickForm\ArgumentRegistry;
use Fab\Vidi\Tca\Tca;

/**
 * View helper which render a TCA form on the FE.
 */
class FormViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper {

	/**
	 * @var string
	 */
	protected $partialRootPath = '';

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('type', 'int', 'The type of the record.', FALSE, 0);
		$this->registerArgument('items', 'array', 'The children items to be rendered inside current', FALSE, array());
		$this->registerArgument('arguments', 'array', 'Arguments to pass to the partial.', FALSE, array());
		$this->registerArgument('index', 'int', 'The current index of the items.', FALSE, NULL);
		$this->registerArgument('dataType', 'string', 'The data type to render, corresponds likely to the table name', FALSE, '');
		$this->registerArgument('validation', 'string', 'Type of validation, possible values are tca, typoscript, model.', FALSE, 'tca');
	}

	/**
	 * Render a TCA form on the FE.
	 *
	 * @throws \Exception
	 * @return string
	 */
	public function render() {

		$result = '';

		$items = $this->getItems();

		foreach ($items as $item) {

			// Convert Quick Form component to array
			if ($item instanceof ComponentInterface) {
				$item = $item->toArray();
			}

			$renderViewHelper = $this->getRenderViewHelper();
			$this->configureView($item);

			if (is_array($item) && isset($item['partial'])) {

				// handle arguments array
				$arguments = $this->getInitialArguments();

				// If the items has its own arguments.
				if (!empty($item['items'])) {
					$arguments = array_merge($arguments, array('items' => $item['items']));
				}

				// If the items has its own arguments.
				if (!empty($item['arguments'])) {
					$arguments = array_merge($arguments, $item['arguments']);
				}

				// Computes partial and section name.
				$partial = $this->computePartialNameForItem($item);
				$section = $this->computeSectionNameForItem($partial);

				$result .= $renderViewHelper->render($section, $partial, $arguments);
			} elseif (TRUE === is_string($item)) { // this is a field.

				$initialArguments = $this->getInitialArguments();
				$fieldType = Tca::table($initialArguments['dataType'])->field($item)->getType();

				if ($fieldType == FieldType::TEXTAREA) {
					$section = 'TextArea';
				} elseif ($fieldType == FieldType::TEXT || $fieldType == FieldType::EMAIL) {
					$section = 'TextField';
				} elseif ($fieldType == FieldType::NUMBER) {
					$section = 'NumberField';
				} elseif ($fieldType == FieldType::DATE) {
					$section = 'DatePicker';
				} elseif ($fieldType == FieldType::SELECT) {
					$section = 'Select';
				} elseif ($fieldType == FieldType::MULTISELECT) {
					$section = 'MultiSelect';
				} elseif ($fieldType == FieldType::CHECKBOX) {
					$section = 'Checkbox';
				} elseif ($fieldType == FieldType::RADIO) {
					$section = 'RadioButtons';
				} else {
					$message = sprintf('Unknown field type: "%s" for field "%s"', $fieldType, $item);
					throw new \Exception($message, 1401954717);
				}

				$partial = $this->computePartialNameForField($section);

				// Merge some default fields.
				$arguments = array_merge(
					$this->getInitialArguments(),
					array('label' => $item),
					array('property' => GeneralUtility::underscoredToLowerCamelCase($item))
				);

				$result .= $renderViewHelper->render($section, $partial, $arguments);
			} else {

				// @todo
				// Log empty array or unknown type
			}
		}

		return $result;
	}

	/**
	 * Computes a partial name for an item.
	 * This method will be overridden in the Show View Helper which proceeds differently.
	 *
	 * @param array $item
	 * @return array
	 */
	protected function computePartialNameForItem($item) {
		return $item['partial'];
	}

	/**
	 * Computes a section name given an item.
	 *
	 * @param string $partial
	 * @return array
	 */
	protected function computeSectionNameForItem($partial) {
		$segments = explode('/', $partial);
		return array_pop($segments);
	}

	/**
	 * Computes a partial name for a field.
	 * This method will be overridden in the Show View Helper which proceeds differently.
	 *
	 * @param string $section
	 * @return string
	 */
	protected function computePartialNameForField($section) {
		return 'Form/' . $section;
	}

	/**
	 * Returns base arguments from the initial VH.
	 *
	 * @throws \Exception
	 * @return array
	 */
	protected function getInitialArguments() {

		$initialArguments = ArgumentRegistry::getInstance()->get();

		// check if not better initialized $argument to NULL in ArgumentRegistry
		if (empty($initialArguments)) {
			if (empty($this->arguments['dataType'])) {
				throw new \Exception('Missing dataType argument for the first call. Forgotten <f.form.tca dataType="tx_domain_xyz" />?', 1385395355);
			}

			$this->arguments['arguments']['dataType'] = $this->arguments['dataType'];
			$this->arguments['arguments']['validationType'] = $this->arguments['validation'];
			$this->arguments['arguments']['type'] = (int)$this->arguments['type']; // add useful variable to be transmitted along the rendering.
			$initialArguments = ArgumentRegistry::getInstance()->set($this->arguments['arguments'])->get();
		}

		return $initialArguments;
	}

	/**
	 * @throws \Exception
	 * @return array
	 */
	protected function getItems() {
		$items = $this->arguments['items'];
		$type = (int)$this->arguments['type'];

		$index = $this->arguments['index'];

		if (empty($items)) {
			$dataType = $this->arguments['dataType'];
			if (0 === $type && empty($GLOBALS['TCA'][$dataType]['quick_form'][$type])) {
				$type++; // try to shift to the next index.
				$this->arguments['type'] = $type;
			}

			if (empty($GLOBALS['TCA'][$dataType]['quick_form'][$type])) {
				$message = sprintf('No TCA configuration found in [quick_form][%s] for data type "%s"',
					$type,
					$dataType
				);
				throw new \Exception($message, 1384703096);
			}
			$items = $GLOBALS['TCA'][$dataType]['quick_form'][$type];
		} elseif (isset($items[$index])) {
			$items = $items[$index];
		}

		$items = $this->sanitizeItems($items);

		return $items;
	}

	/**
	 * Makes sure the array is well formatted.
	 *
	 * @param array $items
	 * @return boolean
	 */
	protected function sanitizeItems($items) {
		if (is_string($items)) {
			$items = GeneralUtility::trimExplode(',', $items, TRUE);
		} elseif ($this->isAssociativeArray($items)) {
			$items = array($items);
		}
		return $items;
	}

	/**
	 * @param $array
	 * @return boolean
	 */
	protected function isAssociativeArray($array) {
		return array_keys($array) !== range(0, count($array) - 1);
	}

	/**
	 * @return \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
	 */
	public function getRenderViewHelper() {

		$renderViewHelper = ObjectFactory::getInstance()->getRenderViewHelper(
			$this->controllerContext,
			$this->templateVariableContainer,
			$this->viewHelperVariableContainer
		);

		return $renderViewHelper;
	}

	/**
	 * Dynamically configure the View.
	 *
	 * @param array|ComponentInterface $item
	 * @return void
	 */
	protected function configureView($item) {

		$partialRootPath = $this->resolvePartialRootPath($item);

		/** @var \TYPO3\CMS\Fluid\View\TemplateView $view */
		$view = $this->viewHelperVariableContainer->getView();
		$view->setPartialRootPath($partialRootPath);
	}

	/**
	 * Compute the partial root path given an item.
	 *
	 * @param array|ComponentInterface $item
	 * @return string
	 */
	protected function resolvePartialRootPath($item) {

		$settings = $this->getSettings();

		// Indicate the extension where to find the partials.
		// Default value is "quick_form" here.
		$extensionKey = $settings['partialExtensionKey'];
		if (is_array($item) && isset($item['partialExtensionKey'])) {
			$extensionKey = $item['partialExtensionKey'];
		}
		return ExtensionManagementUtility::extPath($extensionKey) . 'Resources/Private/Partials/';
	}

	/**
	 * Returns the validation configuration
	 *
	 * @return string
	 */
	protected function getSettings() {

		$configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		return $configuration['plugin.']['tx_quickform.']['settings.'];
	}
}
