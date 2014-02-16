Quick Form for TYPO3 CMS
========================

This is a TYPO3 CMS extension which allows to quickly build a form on the Frontend that corresponds to a data type describe by the TCA.

The form can be used with an Extbase but not necessary.


Installation
============

Installation as "normal" in the Extension Manager.


Configuration
=============

Settings are done by TypoScript in EXT:quick_form/Configuration/TypoScript/setup.txt.

* key "partialExtension": - Tells where to find the Partials. Starting a new project it is encouraged to copy / paste the Partials
  from EXT:quick_form into your own extension and adjust the HTML markup there. Make sure to respect the path convention. - default "quick_form"
* key "validate" - Configuration for form validation by TypoScript. - default empty

View Helpers
============

Edit form
---------

There is a View Helper that will read the TCA configuration and will generate the form on the Frontend::

	<qf:tca.form type="1" arguments="{_all}" dataType="fe_users"/>

Important to notice, the View Helper will not generate the form tag giving the full flexibility of the action and controller.
In other words, the VH must be wrapped by a ``f:form`` tag as in the example::

	# Regular Extbase Form
	<f:form action="update" controller="User" name="user" object="{user}" additionalAttributes="{role: 'form'}">

		<qf:tca.form type="1" arguments="{_all}" dataType="fe_users" validation="tca"/>

	</f:form>


Show form (pre-visualisation)
-----------------------------

For pre-visualisation needs, the ``tca.show`` can be used. It will display the visualisation (read-only) instead of the field for editing.

::

	<f:form action="create" controller="User" name="user" object="{user}" additionalAttributes="{role: 'form'}">

		# tca.show != tca.form
		<qf:tca.show type="1" arguments="{_all}" dataType="fe_users"/>

	</f:form>

Flexible Validation
===================

Quick Form will show information on the Frontend whether the field contains validation. This is for now limited to: is required? has error after submit?

It is configured to add a well-known "*" to
show the field is required.

To get the validation info, Quick Form has three possible sources that can be configured with attribute "validation".
If nothing is configured Quick Form will take the TCA as fallback which has the advantage to keep in sync the Backend and the Frontend.

* tca: the default value, check validation against TCA.
* typoscript: Extbase makes it quite complicated to validate a model having different types.
  To work around typoscript validation can be used. Configuration must be given in ``plugin.tx_quickform.validate``
* object: use the object provided by context and use reflection to tell what fields are required.
* MyExtension\Domain\Model\Foo: an object is not always available in the context. A model name can be provided.


@todo add more validation output such as string length, email, ...


TCA configuration
=================

In file ``EXT:sample/Configuration/TCA/tx_sample_domain_model_foo`` make sure to have a section like::

	# Adequate for "flat" structure.
	return array(
		'feInterface' => array(
			'types' => array(
				'1' => 'first_name, last_name, ...',
			),
		),

TCA configuration can be more complex by accepting nested structure::

	# Adequate for "nested" structure which contains field-set and other containers.
	return array(
		'feInterface' => array(
			'types' => array(
				'1' => array(
					'partial' => 'Form/FieldSet',
					'items' => array(
						'first_name',
						'last_name',
						array(
							'partial' => 'Form/Submit',
						),
					),
				),
			),
		),

Quick Form also accepts a syntax with object that is a bit more concise than array and that is convenient
when working with an IDE which auto-complete parameters::

	# Usage of a Quick Form Component
	return array(
		'feInterface' => array(
			'types' => array(
				'1' => array(
					'partial' => 'Form/FieldSet',
					'items' => array(
						'first_name',
						'last_name',
						new \TYPO3\CMS\QuickForm\Component\SubmitComponent()
					),
				),
			),
		),

Use "external" Partials
=======================

Partials within EXT:quick_start are taken as defaults. However, it is possible to use "external" Partials located in
another extension::

	new \TYPO3\CMS\QuickForm\Component\GenericComponent('Form/Foo', array('property' => 'propertyName'), 'foo'),

* The first parameter corresponds to the Partial Name
* The second to the arguments
* The third is the extension where the Partials come from


Quick Form Components
=====================

A list of all components that can be displayed in a Quick Form. Some of them are a bit more complex to set-up as they need
a good TCA configuration along with a correct Extbase code. For those ones, there is code example given below.

* Checkbox
* CheckboxGroup (with example)
* DatePicker (with example)
* FieldSet
* FileUpload (with example)
* Hidden
* MultiChoices (with example)
* MultiSelect
* Navigation
* NavigationFirst
* NavigationLast
* NumberField
* RadioButtons
* Select
* Separator
* Submit
* TabPanel
* Text
* TextArea
* TextField
* Todo (component for just showing some temporary message on the Frontend)

.. ................................................................................................

CheckboxGroup
-------------

::

		array(
			'partial' => 'Form/CheckboxGroup',
			'arguments' => array('label' => 'wheels_or_tracks'),
			'items' => array(
				'operational_data_wheels',
				'operational_data_tracks',
			),
		),

TCA configuration
+++++++++++++++++

::


		'operational_data_wheels' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.operational_data_wheels',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			),
		),
		'operational_data_tracks' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.operational_data_tracks',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			),
		),

Extbase code
++++++++++++

::

	/**
	 * @var int
	 * @validate Integer
	 */
	protected $operationalDataWheels = 0;

	/**
	 * @var int
	 * @validate Integer
	 */
	protected $operationalDataTracks = 0;

.. ................................................................................................

DatePicker
----------

::


Some more JavaScript is required here. To be found a jQuery plugin for Bootstrap available in this
repository in branch "bs3" as of this writing. https://github.com/eternicode/bootstrap-datepicker/tree/bs3


TCA configuration
+++++++++++++++++

::

		'available_on_market_from' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.available_on_market_from',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'max' => 20,
				'eval' => 'date',
				'default' => '0'
			)
		),

Extbase code
++++++++++++

	/**
	 * @var \DateTime
	 */
	protected $availableOnMarketFrom;

.. ................................................................................................

Multi Choice
------------

::

	new \TYPO3\CMS\QuickForm\Component\MultiChoicesComponent('protectionLevel'),

TCA configuration
+++++++++++++++++

::

	'protection_level' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.protection_level',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array('', ''),
				array('LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.protection_level.imas', '1'),
				array('LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.protection_level.stanag', '2'),
				array('LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.protection_level.mil', '3'),
				array('LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_equipment.protection_level.other', '4'),
			),
			'size' => 4,
			'maxitems' => 10,
			'eval' => ''
		),
	),

Extbase code
++++++++++++

Property::

	/**
	 * @var int
	 */
	protected $protectionLevel;


.. ................................................................................................

File Upload
-----------

::

	new \TYPO3\CMS\QuickForm\Component\FileUploadComponent('logo'),

TCA configuration
+++++++++++++++++

::

	'logo' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_ext_domain_model_organisation.logo',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'logo',
				array(
					'appearance' => array(
						'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
					),
					'minitems' => 0,
					'maxitems' => 1,
				),
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
		),
	),


Extbase code
++++++++++++

Property::

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $logo;

Accessor::

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	public function getLogo() {
		return $this->logo;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $logo
	 */
	public function setLogo($logo) {
		$this->logo = $logo;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $logo
	 * @return void
	 */
	public function addLogo(ObjectStorage $logo) {
		$this->logo->attach($logo);
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $logo
	 * @return void
	 */
	public function removeLogo(ObjectStorage $logo) {
		$this->logo->detach($logo);
	}


