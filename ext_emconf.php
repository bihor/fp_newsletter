<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "fp_newsletter"
 *
 * Auto generated by Extension Builder 2018-06-15
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Newsletter subscriber management',
    'description' => 'Plugin for newsletter subscription and unsubscription with double opt in (and double opt out). For: mail or luxletter / tt_address or fe_users. A log is written.',
    'category' => 'plugin',
    'author' => 'Kurt Gusbeth',
    'author_company' => 'fixpunkt für digitales GmbH',
    'state' => 'stable',
    'version' => '8.0.1',
    'constraints' => [
        'depends' => [
        	'typo3' => '13.4.0-13.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
