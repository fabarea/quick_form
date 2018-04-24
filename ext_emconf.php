<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Quick Form',
    'description' => 'Generate quick form on the Frontend based on TCA',
    'category' => 'fe',
    'author' => 'Fabien Udriot',
    'author_email' => 'fabien.udriot@typo3.org',
    'state' => 'beta',
    'version' => '2.0.0',
    'autoload' => [
        'psr-4' => ['Vanilla\\QuickForm\\' => 'Classes']
    ],
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '8.7.0-8.7.99',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                    'media_upload' => '',
                ],
        ],
];
