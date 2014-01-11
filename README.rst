Quick Form for TYPO3 CMS
=====================================

This is a TYPO3 CMS extension which allows to quickly build a form on the Frontend that corresponds to a data type describe by the TCA.

The form can be used with an Extbase but not necessary.


Installation
=================

Installation as "normal" in the Extension Manager


View Helpers
=================

There is a View Helper that will read the TCA configuration and will generate the form on the Frontend::

	<qf:tca.form type="1" arguments="{_all}" dataType="tx_lima_domain_model_contact"/>

Important to notice, the View Helper will not generate the form tag giving the full flexibility of the action and controller.
In other words, the VH must be wrapped by a ``f:form`` tag as in the example::

	# Regular Extbase Form
	<f:form action="update" controller="Contact" name="contact" object="{contact}" addQueryString="0" noCacheHash="1"
	        additionalAttributes="{role: 'form'}">

		# Use of TCA View Helper
		<qf:tca.form type="1" arguments="{_all}" dataType="tx_lima_domain_model_contact"/>

	</f:form>


Flexible Validation
====================

Quick Form will show information on the Frontend whether the field contains validation. This is for now limited to: is required? has error after submit?

It is configured to add a well-known "*" to
show the field is required.

To get the validation info, Quick Form has three possible sources that can be configured with attribute "validation". There
are three possibles values:
The sources can not be combined. If nothing is configured Quick Form
will take the TCA as fallback source which has the advantage to keep in sync the Backend and the Frontend

* Domain Model: attribute model="MyExtension\Domain\Model\Foo" must be given in the View Helper **unless** you assign the object in the View. The model name can the be retrieved.
* TypoScript: this is useful when multi-type validation is required
* TCA field: the fall-back

@todo add more validation output such as string length, email, ...

Show after submit (multi-steps)
====================================

If Quick Form detects being within a "show" action, it will display the visualisation of the form element (read-only) instead of the field for editing.
This can be useful if the user wants to review the data before updating.


TCA configuration
==================

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
when working with an IDE which auto-complete parameters.

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
=================================

Partials within EXT:quick_start are taken as defaults. However, it is possible to use "external" Partials located in
another extension::

	new \TYPO3\CMS\QuickForm\Component\GenericComponent('Form/BecomeContact', array('property' => 'propertyName'), 'foo'),

* The first parameter corresponds to the Partial Name
* The second to the arguments
* The third is the extension where the Partials come from
