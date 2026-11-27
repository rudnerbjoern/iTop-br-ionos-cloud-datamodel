<?php
/** Register IONOS-specific fields and sample records for the Cloud data model. */

SetupWebPage::AddModule(__FILE__, 'br-ionos-cloud-datamodel/0.4.0', [
    'label' => 'IONOS Cloud data model',
    'category' => 'business',
    'itop_version' => '3.3.0',
    'dependencies' => [
        'br-cloud-datamodel/0.3.0',
    ],
    'mandatory' => false,
    'visible' => true,
    'datamodel' => [],
    'webservice' => [],
    'data.struct' => [],
    'data.sample' => ['data/data.sample.ionos.xml'],
    'doc.manual_setup' => '',
    'doc.more_information' => '',
    'settings' => [],
]);
