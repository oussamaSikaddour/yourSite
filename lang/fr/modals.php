<?php

return [

    'user' => [
        'actions' => [
            'add' => "Créer un utilisateur",
            'update' => "Modifier l'utilisateur : :name",
            'manage' => [
                'roles' => 'Gérer les rôles : :name',
            ],
        ],
    ],
    'person' => [
        'actions' => [
            'add' => "Ajouter du personnel",
            'update' => "Modifier le personnel : :name",
            'manage' => [
                'occupations' => 'Gérer les occupations : :name',
                'banking_information' => 'Gérer les informations bancaires : :name',
                'account' => 'Gérer le compte : :name',
            ],
        ],
    ],
    "banking_info" => [
        "actions" => [
            "add" => "Ajouter des détails bancaires",
            "update" => "Mettre à jour les informations bancaires"
        ]
    ],

    'field' => [
        'actions' => [
            'add' => 'Créer un nouveau domaine professionnel',
            'update' => 'Mettre à jour le domaine : :acronym',
            'manage' => [
                'grades' => 'Gérer les niveaux de grade',
                'specialties' => 'Gérer les spécialisations',
            ],
        ],
    ],
    'wilaya' => [
        'actions' => [
            'add' => 'Ajouter une nouvelle wilaya',
            'update' => 'Mettre à jour la wilaya : :code',
            'manage' => [
                'view' => 'Voir les détails de la wilaya',
            ],
        ],
    ],
    'daira' => [
        'actions' => [
            'add' => 'Ajouter une nouvelle daïra',
            'update' => 'Mettre à jour la daïra : :code',
        ],
    ],
    'bank' => [
        'actions' => [
            'add' => 'Ajouter une nouvelle banque',
            'update' => 'Mettre à jour la banque sélectionnée',
        ],
    ],
    'menu' => [
        'actions' => [
            'add' => 'Ajouter un nouveau menu',
            'update' => 'Mettre à jour le menu sélectionné',
        ],
    ],
    'external_link' => [
        'actions' => [
            'add' => 'Ajouter un nouveau lien externe',
            'update' => 'Mettre à jour le lien externe sélectionné',
        ],
    ],
    "our_quality" => [
        "actions" => [
            "new" => "Ajouter une nouvelle qualité",
            "update" => "Mettre à jour la qualité sélectionnée"
        ]
    ],
    "message" => [
        "actions" => [
            "reply" => "Envoyer une réponse"
        ]
    ],
    'slider' => [
        'actions' => [
            'add' => 'Ajouter un nouveau diaporama pour :name',
            'update' => 'Mettre à jour le diaporama :name',
        ],
    ],
    'slide' => [
        'actions' => [
            'add' => 'Ajouter une nouvelle diapositive',
            'update' => 'Mettre à jour la diapositive sélectionnée',
        ],
    ],
'article' => [
    'actions' => [
        'add' => 'Ajouter un nouvel article pour :name',
        'update' => 'Mettre à jour l\'article :title',
    ],
],
    'trend' => [
        'actions' => [
            'add' => 'Ajouter une nouvelle tendance',
            'update' => 'Mettre à jour la tendance sélectionnée',
        ],
    ],
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'service' => [
        'actions' => [
            'add' => 'Ajouter un nouveau service',
            'update' => 'Mettre à jour le département :name',
            "manage_coordinators" => "Gérer les coordinateurs de :name",
        ],
    ],
];
