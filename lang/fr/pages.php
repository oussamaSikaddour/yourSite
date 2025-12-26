<?php

return [

    'site_parameters' => [
        'name' => 'Paramètres du site',
        'titles' => [
            'main' => 'Paramètres du site',
        ],
    ],
    "login" => [
        "name" => "Connexion",

        'links' => [
            'register' => 'Nouveau sur ' . config('app.name') . ' ? Inscrivez-vous maintenant',
            'forgot_password' => 'Mot de passe oublié?',
        ],

        "titles" => [
            'main' => 'Se connecter',
        ]
    ],
    'register' => [
        'name' => 'Inscription',
        'links' => [
            'login' => 'Vous avez déjà un compte?',
        ],
        'titles' => [
            'main' => 'S\'inscrire',
        ]
    ],
    "logout" => "Se déconnecter",
    'forgot_password' => [
        'name' => 'Mot de passe oublié',
        'titles' => [
            "main" => 'Récupérer votre compte',
        ]
    ],
    "profile" => [
        'name' => "Profil",
        "titles" => [
            "main" => "Bienvenue dans votre profil"
        ]
    ],

    "change_password" => [
        'name' => "Changer le mot de passe",
        "titles" => [
            "main" => "Changer votre mot de passe"
        ]
    ],
    "change_email" => [
        "name" => "Changer l'email",
        "titles" => [
            "main" => "Changer votre email"
        ]
    ],

    "dashboard" => [
        'name' => "Tableau de bord",
        "titles" => [
            "main" => "Bienvenue sur le tableau de bord :name"
        ]
    ],
    "super_admin_space" => [
        'name' => "Tableau de bord Super Admin",
        "titles" => [
            "main" => "Bienvenue sur le tableau de bord Super Admin"
        ]
    ],


    "manage_users" => [
        'name' => "Gérer les utilisateurs",
        "titles" => [
            "main" => "Gérer les utilisateurs"
        ]
    ],
    "manage_persons" => [
        'name' => "Gérer le personnel",
        "titles" => [
            "main" => "Gérer le personnel"
        ]
    ],
    "manage_social_works" => [
        'name' => "Travaux sociaux",
        "titles" => [
            "main" => "Gérer les travaux sociaux"
        ]
    ],
    "wilaya" => [
        'name' => "Wilayas",
        "titles" => [
            "main" => "Gestion des wilayas"
        ]
    ],
    "dairates" => [
        'name' => "Dairas",
        "titles" => [
            "main" => "Gestion des dairas (Code wilaya: :code)"
        ]
    ],
    "occupation_fields" => [
        'name' => "Domaines professionnels",
        "titles" => [
            "main" => "Gérer les domaines professionnels"
        ]
    ],


    "general_infos" => [
        "name" => "Gérer les informations générales",
        "titles" => [
            "main" => "Gérer les informations générales de l'application"
        ],
    ],
    "manage_hero" => [
        "name" => "Gérer la section Hero",
        "titles" => [
            "main" => "Gérer la section Hero"
        ],
    ],
    "manage_about_us" => [
        "name" => "Gérer la section À propos",
        "titles" => [
            "main" => "Gérer la section À propos"
        ],
    ],
    "manage_our_qualities" => [
        "name" => "Gérer la section Nos qualités",
        "titles" => [
            "main" => "Gérer la section Nos qualités"
        ],
    ],

    'messages' => [
        'name' => 'Messages des visiteurs',
        'titles' => [
            'main' => 'Boîte de réception des messages des visiteurs',
        ],
    ],
    'banks' => [
        'name' => 'Gérer les banques',
        'titles' => [
            'main' => 'Gérer les banques',
        ],
    ],

    'articles' => [
        'name' => 'Gérer les articles',
        'titles' => [
            'main' => 'Gérer les articles',
        ],
    ],

    'menus' => [
        'name' => 'Gérer les menus',
        'titles' => [
            'main' => 'Gérer les menus',
        ],
    ],

    'menu' => [
        'name' => 'Gérer le menu',
        'titles' => [
            'main' => 'Gérer les liens externes du menu :title',
        ],
    ],
    'sliders' => [
        'name' => 'Gérer les sliders',
        'titles' => [
            'main' => 'Gérer les sliders',
        ],
    ],
    'slider' => [
        'name' => 'Gérer les diapositives',
        'titles' => [
            'main' => 'Gérer les diapositives :name',
        ],
    ],
    'trends' => [
        'name' => 'Gérer les tendances',
        'titles' => [
            'main' => 'Gérer les tendances',
        ],
    ],

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           App

    "landing_page" => [
        "name" => "Page d'accueil",
        "links" => [
            'hero' => "Hero",
            'about_us' => "À propos",
            "services" => "Nos départements",
            'contact_us' => "Contactez-nous"
        ],
        "sections" => [
            "hero" => [
                "call_to_actions" => [
                    'contact_us' => "Contactez-nous",
                    'get_started' => "Commencer le parcours"
                ]
            ],
            "about_us" => [
                "title" => "À propos de nous",
                "beds" => "Lits",
                "services" => "Départements",
                "years" => "Années"
            ],
            "services" => [
                'title' => "Nos départements",
                'sub_title' => "Engagés dans la qualité, les soins et la fiabilité"
            ],
            'contact_us' => [
                'title' => "Contactez-nous",
                'sub_title' => "Veuillez fournir une adresse email valide pour que nous puissions répondre à votre demande.",
                'coordinates' => "Nos coordonnées",
                'location' => "Adresse",
                'email' => "Email",
                'phone' => "Téléphone",
                'fax' => "Fax",
            ],
            "footer" => [
                'copyright' => "Copyright",
                "agency" => ":name",
                "reservation" => "Tous droits réservés",
                'designed_by' => "Conçu par",
                "links" => [
                    "privacy_policy" => "Politique de confidentialité",
                    'terms_of_service' => "Conditions d'utilisation",
                    'cookie_policy' => "Politique des cookies"
                ]
            ]
        ]
    ],

    "services_public" => [
        "name" => "Nos départements",
        'title' => "Nos départements",
    ],
    "service_details_public" => [
        "name" => "Notre département",
        'title' => "Détails du département :name",
    ],
    'services' => [
        'name' => 'Gérer les départements',
        'titles' => [
            'main' => 'Gérer les départements',
        ],
    ],
    'service' => [
        'default' => [
            "name" => "Gérer le département",
            "titles" => [
                "main" => "Gérer le département"
            ]
        ],
        'name' => ':name',
        'titles' => [
            'main' => 'Gérer le département :name',
        ],
    ],
    'bonuses' => [
        'name' => 'Gérer les bonus',
        'titles' => [
            'main' => 'Gérer les bonus',
        ],
    ],
    'global_transfers' => [
        'name' => 'Gérer les transferts globaux',
        'titles' => [
            'main' => 'Gérer les transferts globaux',
        ],
    ],
    'global_transfer_details' => [
        'name' => 'Détails du transfert global',
        'titles' => [
            'main' => 'Gérer les détails du transfert global :motive',
        ],
    ],
];
