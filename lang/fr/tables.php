<?php

return [
    "common" => [
        "excel-file-type-err" => "Le fichier doit être au format Excel (XLSX, XLS, CSV)",
        "actions" => "Actions",
        "perPage" => "Par page"
    ],

    'images' => [
        "info" => "Liste des fichiers image",
        "not_found" => "Aucun fichier image trouvé",
        'display_name' => "Nom d'affichage",
        "use_case" => "Cas d'utilisation",
        'created_at' => "Ajouté le",
        'preview' => "Aperçu",
    ],
    'files' => [
        "info" => "Liste des fichiers PDF",
        "not_found" => "Aucun fichier PDF trouvé",
        'display_name' => "Nom d'affichage",
        "use_case" => "Cas d'utilisation",
        'created_at' => "Ajouté le",
        'preview' => "Aperçu",
        "download" => "Télécharger le fichier"
    ],

    'users' => [
        "info" => 'Registre des utilisateurs',
        "not_found" => "Aucun utilisateur disponible",
        "name" => "Nom d'utilisateur",
        "email" => "Adresse email",
        "avatar" => "Profil",
        "registration_date" => "Compte créé le",
        "excel" => [
            "upload" => [
                "success" => "Utilisateurs importés avec succès"
            ]
        ]
    ],
    'persons' => [
        "info" => "Registre du personnel",
        "empty" => "Aucun dossier personnel trouvé",
        "full_name" => "Nom complet",
        "full_name_fr" => "Nom complet (FR)",
        "full_name_ar" => "Nom complet (AR)",
        "employee_number" => "Identifiant employé",
        "social_number" => "Numéro de sécurité sociale",
        "email" => "Email officiel",
        "registration_date" => "Date d'enregistrement",
        "phone" => "Téléphone",
        "card_number" => "Numéro national d'identité",
        "birth_date" => "Date de naissance",
        "birth_place_fr" => "Lieu de naissance (FR)",
        "birth_place_ar" => "Lieu de naissance (AR)",
        "birth_place_en" => "Lieu de naissance (EN)",
        "excel" => [
            "upload" => [
                "success" => "Dossiers du personnel importés avec succès"
            ]
        ]
    ],
    'our_qualities' => [
        'info' => 'Liste de nos qualités',
        'not_found' => 'Aucune qualité trouvée pour le moment',
        'created_at' => 'Date d\'ajout',
        'name' => 'Nom',
        'status' => 'Statut',
        'errors' => [
            'active_limit' => 'Seulement 4 qualités peuvent être actives pour être affichées aux visiteurs du site',
        ],
    ],
    'messages' => [
        'info' => 'Messages des visiteurs',
        'not_found' => 'Aucun message de visiteur trouvé pour le moment',
        'name' => 'Nom',
        'email' => 'Email',
        'created_at' => 'Date de réception',
    ],

    'wilayates' => [
        "info" => "Liste des wilayas",
        "not_found" => "Aucune wilaya trouvée pour le moment",
        "code" => "Code",
        "designation" => "Désignation",
        "designation_fr" => "Désignation (Français)",
        "designation_ar" => "Désignation (Arabe)",
        "designation_en" => "Désignation (Anglais)",
        "registration_date" => "Date d'enregistrement",
        "excel" => [
            "upload" => [
                "success" => "Liste des wilayas téléchargée avec succès"
            ]
        ]
    ],
    'dairates' => [
        "info" => "Districts de la wilaya (Code: :code)",
        "not_found" => "Aucun district disponible pour le moment",
        "code" => "Code de la daïra",
        "designation" => "Nom de la daïra",
        "designation_fr" => "Nom français",
        "designation_ar" => "Nom arabe",
        "designation_en" => "Nom anglais",
        "registration_date" => "Date d'enregistrement",
        "excel" => [
            "upload" => [
                "success" => "Données des districts importées avec succès"
            ]
        ]
    ],
    'communes' => [
        "info" => "Communes de la daïra (Code: :code)",
        "not_found" => "Aucune commune disponible pour le moment",
        "code" => "Code de la commune",
        "designation" => "Nom de la commune",
        "designation_fr" => "Nom français",
        "designation_ar" => "Nom arabe",
        "designation_en" => "Nom anglais",
        "registration_date" => "Date d'enregistrement",
        "excel" => [
            "upload" => [
                "success" => "Données des communes importées avec succès"
            ]
        ]
    ],
    'fields' => [
        "info" => "Liste des domaines",
        "not_found" => "Aucun domaine trouvé pour le moment",
        "acronym" => "Acronyme",
        "designation" => "Désignation",
        "designation_fr" => "Désignation (Français)",
        "designation_ar" => "Désignation (Arabe)",
        "designation_en" => "Désignation (Anglais)",
        "registration_date" => "Date d'enregistrement",
        "excel" => [
            "upload" => [
                "success" => "Liste des domaines téléchargée avec succès"
            ]
        ]
    ],
    'field_grades' => [
        "info" => "Niveaux de grade pour le domaine : :acronym",
        "not_found" => "Aucun niveau de grade disponible pour le moment",
        "acronym" => "Code du grade",
        "designation" => "Titre du grade",
        "designation_fr" => "Titre français",
        "designation_ar" => "Titre arabe",
        "designation_en" => "Titre anglais",
        "registration_date" => "Date d'enregistrement",
        "excel" => [
            "upload" => [
                "success" => "Niveaux de grade importés avec succès"
            ]
        ]
    ],
    'field_specialties' => [
        "info" => "Spécialités professionnelles : :acronym",
        "not_found" => "Aucune spécialité disponible pour le moment",
        "acronym" => "Code de spécialité",
        "designation" => "Titre de spécialisation",
        "designation_fr" => "Titre français",
        "designation_ar" => "Titre arabe",
        "designation_en" => "Titre anglais",
        "registration_date" => "Date d'enregistrement",
        "excel" => [
            "upload" => [
                "success" => "Spécialisations importées avec succès"
            ]
        ]
    ],

    'occupations' => [
        "info" => "Liste des occupations",
        "info_custom" => "Liste des occupations de :name",
        "not_found" => "Aucune occupation trouvée pour le moment",
        "is_active" => "État",
        "entitled" => "Intitulé",
        "field" => "Domaine",
        "experience" => "Expérience",
        "specialty" => "Spécialité",
        "grade" => "Grade",
        "created_at" => "Ajouté le",
    ],
    'banking_information' => [
        "info" => "Liste des informations bancaires",
        "info_custom" => "Informations bancaires de :name",
        "not_found" => "Aucune information bancaire trouvée pour le moment",
        "bank_acronym" => "Banque",
        "agency" => "Agence",
        "agency_code" => "Code d'agence",
        "account_number" => "Numéro de compte",
        "is_active" => "État",
        "created_at" => "Ajouté le",
    ],

    'banks' => [
        "info" => "Répertoire des banques",
        "not_found" => "Aucune banque disponible pour le moment",
        'code' => "Code bancaire",
        'acronym' => "Acronyme de la banque",
        "designation" => "Nom de la banque",
        "designation_fr" => "Nom français",
        "designation_ar" => "Nom arabe",
        "designation_en" => "Nom anglais",
        "created_at" => "Date d'enregistrement",
    ],
    'menus' => [
        "info" => "Liste des menus",
        "not_found" => "Aucun menu trouvé pour le moment",
        "title" => "Titre",
        "state" => "État",
        "type" => "Type",
        "created_at" => "Ajouté le",
    ],
    'external_links' => [
        "info" => "Liste des liens externes",
        "not_found" => "Aucun lien externe trouvé pour le moment",
        "name" => "Nom",
        "url" => "URL",
        "created_at" => "Ajouté le",
    ],
    'articles' => [
        "info" => "Liste des articles",
        "not_found" => "Aucun article trouvé pour le moment",
        "created_at" => "Ajouté le",
        'author' => "Auteur",
        'title' => "Titre",
        "articleable_type" => "Publié pour",
        "articleable_id" => "Publié dans",
        "location" => "Localisation",
        "state" => "État",
    ],
    'sliders' => [
        "info" => "Liste des sliders",
        "not_found" => "Aucun slider trouvé",
        "created_at" => "Ajouté le",
        'creator' => "Créateur",
        'name' => "Nom",
        "position" => "Position",
        "location" => "Localisation",
        "state" => "Statut",
    ],
    "slides" => [
        "info" => "Liste des sliders",
        "not_found" => "Aucun slider trouvé pour le moment",
        "created_at" => "Ajouté le",
        'title' => "Titre",
        'order' => 'Ordre',
        'image' => "Image",
        "location" => "Localisation",
        "state" => "État",
    ],
    "trends" => [
        "info" => "Liste des tendances",
        "not_found" => "Aucune tendance trouvée pour le moment",
        "created_at" => "Ajouté le",
        'title' => "Titre",
        "state" => "État",
    ],

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           App
    'services' => [
        "info" => "Liste des départements de l'établissement",
        "not_found" => "Aucun département enregistré pour le moment",
        "created_at" => "Date d'enregistrement",
        "name" => "Nom du département",
        "name_fr" => "Nom du département (Français)",
        "name_en" => "Nom du département (Anglais)",
        "name_ar" => "Nom du département (Arabe)",
        "tel" => "Téléphone principal",
        "fax" => "Fax",
        'email' => "Email",
        'beds_number' => "Nombre de lits",
        'specialists_number' => "Nombre de spécialistes",
        'physicians_number' => "Nombre de médecins",
        'paramedics_number' => "Nombre de paramédicaux",
        "head_service" => "Chef de département",
        "specialty" => "Spécialité médicale",
        "excel" => [
            "upload" => [
                "success" => "Départements importés avec succès"
            ]
        ]
    ],
    'global_transfers' => [
        "info" => "Aperçu des transferts globaux",
        "not_found" => "Aucun transfert global disponible",
        "initiator" => "Initiateur",
        "motive" => "Motif",
        "total_amount" => "Montant total",
        "reference" => "Référence",
        'date' => "Date de transfert",
        'number' => "Numéro de transfert",
        'date_min' => "À partir du",
        'date_max' => "Jusqu'au",
        "created_at" => "Date de création",
    ],

    'bonuses' => [
        "info" => "Liste des bonus",
        "not_found" => "Aucun bonus disponible",
        "titled" => "Titre",
        "amount" => "Montant",
        "created_at" => "Date de création",
    ],
    'transfers' => [
        "info" => "Liste des transferts - :motive",
        "not_found" => "Aucun transfert disponible",
        "beneficiary" => "Bénéficiaire",
        "bank" => "Banque",
        'account' => "Numéro de compte",
        'amount' => "Montant",
        "excel" => [
            "upload-success" => "Fichier Excel traité avec succès"
        ],
        "errors" => [
            "bonuses" => [
                "not_selected" => "Aucun bonus sélectionné",
                "not_set" => "Veuillez sélectionner des bonus dans le tableau ci-dessus",
                "empty" => "Ajoutez des transferts avant d'attribuer des bonus"
            ],
            "not_found" => [
                "active_establishment_banking_info" => "Veuillez ajouter des informations bancaires à l'établissement ou activer les détails existants"
            ]
        ]
    ],
];
