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
		'searchFields' => 'gender,title,firstname,lastname,email,status,securityhash',
        'iconfile' => 'EXT:fp_newsletter/Resources/Public/Icons/tx_fpnewsletter_domain_model_log.gif'
    ],
    'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, gender, title, firstname, lastname, email, address, zip, city, region, country, phone, mobile, fax, www, birthday, position, company, categories, status, securityhash, retoken, mathcaptcha, gdpr'],
    ],
    'columns' => [
    	'sys_language_uid' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
    		'config' => [
    			'type' => 'select',
    			'renderType' => 'selectSingle',
    			'special' => 'languages',
    			'items' => [
    				[
    					'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
    					-1,
    					'flags-multiple'
    				]
    			],
    			'default' => 0,
    		],
    	],
    	'l10n_parent' => [
    		'displayCond' => 'FIELD:sys_language_uid:>:0',
    		'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
    		'config' => [
    			'type' => 'select',
    			'renderType' => 'selectSingle',
    			'default' => 0,
    			'items' => [
    				['', 0],
    			],
    			'foreign_table' => 'tx_fpnewsletter_domain_model_log',
    			'foreign_table_where' => 'AND {#tx_fpnewsletter_domain_model_log}.{#pid}=###CURRENT_PID### AND {#tx_fpnewsletter_domain_model_log}.{#sys_language_uid} IN (-1,0)',
    		],
    	],
    	'l10n_diffsource' => [
    		'config' => [
    			'type' => 'passthrough',
    		],
    	],
    	'hidden' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
    		'config' => [
    			'type' => 'check',
    			'renderType' => 'checkboxToggle',
    			'items' => [
    				[
    					0 => '',
    					1 => '',
    					'invertStateDisplay' => true
    				]
    			],
    		],
    	],
        'tstamp' => [
            'exclude' => true,
            #'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.tstamp',
            'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
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
			        ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.gender.divers', 3],
			    ],
                'default' => 0,
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
    	'address' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.address',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'zip' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.zip',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'city' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.city',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'region' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.region',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'country' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.country',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'phone' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.phone',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'mobile' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.mobile',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'fax' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.fax',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'www' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.www',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'position' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.position',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'company' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.company',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
    		],
    	],
    	'categories' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.categories',
    		'config' => [
    			'type' => 'input',
    			'size' => 30,
    			'eval' => 'trim'
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
                    ['LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.8', 8],
			    ],
                'default' => 0,
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
    	'retoken' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.retoken',
    		'config' => [
    			'type' => 'text',
    			'cols' => 40,
    			'rows' => 5,
    			'eval' => 'trim'
    		]
    	],
    	'mathcaptcha' => [
    		'exclude' => true,
    		'label' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.mathcaptcha',
    		'config' => [
    			'type' => 'input',
    			'size' => 5,
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
