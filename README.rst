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


TCA configuration
==================

In file ``EXT:sample/Configuration/TCA/tx_sample_domain_model_foo`` make sure to have a section like::

	return array(
		'interface' => array(
			'types' => array(
				'1' => 'first_name, last_name, ...',
			),
		),

TCA configuration can be more complex by accepting nested structure::

	return array(
		'interface' => array(
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

Examples
=================

::

	# Regular Extbase Form
	<f:form action="update" controller="Contact" name="contact" object="{contact}" addQueryString="0" noCacheHash="1"
	        additionalAttributes="{role: 'form'}">

		# Use of TCA View Helper
		<qf:tca.form type="1" arguments="{_all}" dataType="tx_lima_domain_model_contact"/>

	</f:form>

