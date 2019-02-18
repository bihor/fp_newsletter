<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log',
        'label' => 'email',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
        ],
		'searchFields' => 'gender,title,firstname,lastname,email,status,securityhash,gdpr',
        'iconfile' => 'EXT:fp_newsletter/Resources/Public/Icons/tx_fpnewsletter_domain_model_log.gif'
    ],
    'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, gender, title, firstname, lastname, email, status, securityhash, gdpr',
    ],
    'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, gender, title, firstname, lastname, email, status, securityhash, gdpr'],
    ],
    'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					]
				],
				'default' => 0,
			],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_fpnewsletter_domain_model_log',
                'foreign_table_where' => 'AND tx_fpnewsletter_domain_model_log.pid=###CURRENT_PID### AND tx_fpnewsletter_domain_model_log.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
		'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
    	'tstamp' => [
    			'exclude' => true,
    			'l10n_mode' => 'mergeIfNotBlank',
    			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.tstamp',
    			'config' => [
    					'type' => 'input',
    					'size' => 13,
    					'max' => 20,
    					'eval' => 'datetime',
    					'checkbox' => 0,
    					'default' => 0,
    					'range' => [
    							'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
    					],
    			],
    	],
        'gender' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.gender',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['--', 0],
		    		['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.gender.mrs', 1],
		    		['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.gender.mr', 2],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'firstname' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.firstname',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'lastname' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.lastname',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'email' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.email',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'status' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.0', 0],
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.1', 1],
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.2', 2],
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.3', 3],
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.4', 4],
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.6', 6],
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.7', 7],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'securityhash' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.securityhash',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'gdpr' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.gdpr',
	        'config' => [
			    'type' => 'check',
			    'items' => [
			        '1' => [
			            '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
			        ]
			    ],
			    'default' => 0
			]
	    ],
    ],
];
