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

* ``tca``: check validation against TCA, default
* ``typoscript``: Extbase makes it quite complicated to validate a model having different types.
  To work around typoscript validation can be used. Configuration must be given in ``plugin.tx_quickform.validate``
* ``object``: use the object provided by context and use reflection to tell what fields are required.
* ``MyExtension\Domain\Model\Foo``: an object is not always available in the context. A model name can be provided.


@todo add more validation output such as string length, email, ...


TCA configuration
=================

In file ``EXT:sample/Configuration/TCA/tx_sample_domain_model_foo`` make sure to have a section like::

	# Adequate for "flat" structure.
	return array(
		'quick_form' => array(
			'1' => 'first_name, last_name, ...',
		),

TCA configuration can be more complex by accepting nested structure::

	# Adequate for "nested" structure which contains field-set and other containers.
	return array(
		'quick_form' => array(
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

Quick Form also accepts a syntax with object that is a bit more concise than array and that is convenient
when working with an IDE which auto-complete parameters::

	# Usage of a Quick Form Component
	return array(
		'quick_form' => array(
			'1' => array(
				'partial' => 'Form/FieldSet',
				'items' => array(
					'first_name',
					'last_name',
					new \Vanilla\QuickForm\Component\SubmitComponent()
				),
			),
		),

Use "external" Partials
=======================

Partials within EXT:quick_start are taken as defaults. However, it is possible to use "external" Partials located in
another extension::

	new \Vanilla\QuickForm\Component\GenericComponent('Form/Foo', array('property' => 'propertyName', 'label' => 'fieldName'), 'foo'),

* The first parameter corresponds to the Partial Name
* The second to the arguments
* The third is the extension where the Partials come from


Override label
==============

In case the Frontend label must be different than in the BE, use option ``alternative_label`` in the arguments of the Form Component::

	array(
		'alternative_label' => 'LLL:EXT:bobst_forms/Resources/Private/Language/locallang.xlf:privacy_satement_label',
	)

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

Checkbox
--------

If checkbox must be specially configured::

		new \Vanilla\QuickForm\Component\CheckboxComponent(
			'hasNewsletterSubscription',
			'has_newsletter_subscription',
			array('group_label' => 'Newsletter')
		),

TCA configuration
+++++++++++++++++

::


		'operational_data_wheels' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_foo_domain_model_foo.operational_data_wheels',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			),
		),
		'operational_data_tracks' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_foo_domain_model_foo.operational_data_tracks',
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


Honey Pot
---------

In TCA::


	new \Vanilla\QuickForm\Component\HoneyPotComponent(),

In Extbase controller::

	/**
	 * @return void
	 * @validate $request \Vanilla\QuickForm\Validator\HoneyPotValidator
	 * @param \Vendor\Extension\Domain\Model\Foo $foo
	 */
	public function createAction(Foo $foo = NULL) {


	}

TCA configuration
+++++++++++++++++

::

		'available_on_market_from' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_foo_domain_model_foo.available_on_market_from',
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

::

	/**
	 * @var \DateTime
	 */
	protected $availableOnMarketFrom;

.. ................................................................................................

Multi Choice
------------

::

	new \Vanilla\QuickForm\Component\MultiChoicesComponent('protectionLevel'),

TCA configuration
+++++++++++++++++

::

	'some_field' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_foo_domain_model_foo.some_field',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array('', ''),
				array('LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_foo_domain_model_foo.some_field.label_1', '1'),
				array('LLL:EXT:ext/Resources/Private/Language/locallang_db.xml:tx_foo_domain_model_foo.some_field.label_2', '2'),
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
	 * @var string
	 */
	protected $someField;

Beware, a special array-to-string converter must be defined in the Controller in order to convert the multi-choice to a CSV chain::

	/**
	 * Initialize object
	 */
	public function initializeAction() {

		// Configure property mapping.
		if ($this->arguments->hasArgument('objectName')) {

			/** @var \Vanilla\QuickForm\TypeConverter\ArrayToStringConverter $typeConverter */
			$typeConverter = $this->objectManager->get('Vanilla\QuickForm\TypeConverter\ArrayToStringConverter');

			$this->arguments->getArgument('request')
				->getPropertyMappingConfiguration()
				->forProperty('someField')
				->setTypeConverter($typeConverter);
		}
	}
.. ................................................................................................

File Upload
-----------

Suggested `EXT:media_upload`_ to use the file upload API in your Extbase controller.

::

	new \Vanilla\QuickForm\Component\FileUploadComponent('logo'),


Media Upload
------------

Require `EXT:media_upload`_ which provides HTML5 file upload widget.

::

	new \Vanilla\QuickForm\Component\MediaUploadComponent('logo'),

.. _EXT:media_upload: https://github.com/fudriot/media_upload

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


