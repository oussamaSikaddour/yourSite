<?php
return [

    'user' => [
        'actions' => [
            'add' => "Create User",
            'update' => "Edit User: :name",
            'manage' => [
                'roles' => 'Manage Roles: :name',
            ],
        ],
    ],
    'person' => [
        'actions' => [
            'add' => "Add Personnel",
            'update' => "Edit Personnel: :name",
            'manage' => [
                'occupations' => 'Manage Occupations: :name',
                'banking_information' => 'Manage Banking: :name',
                'account' => 'Manage Account: :name',
            ],
        ],
    ],
    "banking_info" => [
        "actions" => [
            "add" => "Add Banking Details",
            "update" => "Update Banking Information"
        ]
    ],

    'field' => [
        'actions' => [
            'add' => 'Create New Professional Field',
            'update' => 'Update Field: :acronym',
            'manage' => [
                'grades' => 'Manage Grade Levels',
                'specialties' => 'Manage Specializations',
            ],
        ],
    ],
    'wilaya' => [
        'actions' => [
            'add' => 'Add New State',
            'update' => 'Update State: :name',
            'manage' => [
                'view' => 'View State Details',
            ],
        ],
    ],
    'daira' => [
        'actions' => [
            'add' => 'Add New District',
            'update' => 'Update District: :name',
             'manage'=> "Manage District :name Communes"
        ],
    ],
    'bank' => [
        'actions' => [
            'add' => 'Add New Bank',
            'update' => 'Update The Selected Bank',
        ],
    ],
    'menu' => [
        'actions' => [
            'add' => 'Add New Menu',
            'update' => 'Update The Selected Menu',
        ],
    ],
    'external_link' => [
        'actions' => [
            'add' => 'Add New External Link',
            'update' => 'Update The Selected External Link',
        ],
    ],
    "our_quality" => [
        "actions" => [
            "new" => "Add New Quality",
            "update" => "Update The Selected Quality"
        ]
    ],
    "message" => [
        "actions" => [
            "reply" => "Send A reply"
        ]
    ],
    'slider' => [
        'actions' => [
            'add' => 'Add New Slider for :name',
            'update' => 'Update The Slider :name ',
        ],
    ],
    'slide' => [
        'actions' => [
            'add' => 'Add New Slide',
            'update' => 'Update The Selected Slide',
        ],
    ],
    'article' => [
        'actions' => [
            'add' => 'Add New Article for :name',
            'update' => 'Update The  Article :title',
        ],
    ],
    'trend' => [
        'actions' => [
            'add' => 'Add New Trend',
            'update' => 'Update The Selected Trend',
        ],
    ],
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'service' => [
        'actions' => [
            'add' => 'Add New Service',
            'update' => 'Update The Service :name',
            "manage_coordinators" => "Manage :name Coordinators",
        ],
    ],

    'bonus' => [
        'actions' => [
            'add' => 'Add New Bonus',
            'update' => 'Update The Bonus',
        ],
    ],
    'global_transfer' => [
        'actions' => [
            'add' => 'Add New Global Transfer',
            'update' => 'Update The Global Transfer',
        ],
    ],
    'transfer' => [
        'actions' => [
            'add' => 'Add New  Transfer',
            'update' => "Update :name ' The Transfer",
        ],
    ],


];
