<?php

return [
    "line_number_error" => "Erreur à la ligne numéro (:number)",
    'users' => [
        'nom_d_utilisateur' => 'Nom d\'utilisateur',
        'e_mail' => 'Adresse email',
    ],
    'persons' => [
        'nom_francais' => 'Nom de famille (FR)',
        'prenom_francais' => 'Prénom (FR)',
        'nom_arabe' => 'Nom de famille (AR)',
        'prenom_arabe' => 'Prénom (AR)',
        'e_mail' => 'Email',
        'compte_bancaire' => 'Numéro de compte',
        'banque' => 'Banque',
    ],

    "banking_information" => [
        "exist" => "Le numéro de compte existe déjà",
        'employee' => [
            'name-mismatch' => 'Le nom de l\'employé ne correspond pas aux enregistrements. Fourni : ":provided", Attendu : ":expected".',
        ],
    ],
    'municipalities' => [
        'code' => "Code de la commune",
        'designation_fr' => "Nom de la commune (Français)",
        'designation_ar' => "Nom de la commune (Arabe)",
        'designation_en' => "Nom de la commune (Anglais)",
    ],
    'dairates' => [
        'code' => "Code de la daïra",
        'designation_fr' => "Nom de la daïra (Français)",
        'designation_ar' => "Nom de la daïra (Arabe)",
        'designation_en' => "Nom de la daïra (Anglais)",
    ],



    'wilayates' => [
        "code" => "Code de la wilaya",
        "designation_fr" => "Nom de la wilaya (Français)",
        "designation_ar" => "Nom de la wilaya (Arabe)",
        "designation_en" => "Nom de la wilaya (Anglais)",
    ],
    'fields' => [
        "acronym" => "Code du domaine",
        "designation_fr" => "Nom du domaine (Français)",
        "designation_ar" => "Nom du domaine (Arabe)",
        "designation_en" => "Nom du domaine (Anglais)",
    ],
    'field_grades' => [
        "acronym" => "Code du grade",
        "designation_fr" => "Titre du grade (Français)",
        "designation_ar" => "Titre du grade (Arabe)",
        "designation_en" => "Titre du grade (Anglais)",
    ],
    'field_specialties' => [
        "acronym" => "Code de spécialité",
        "designation_fr" => "Titre de spécialité (Français)",
        "designation_ar" => "Titre de spécialité (Arabe)",
        "designation_en" => "Titre de spécialité (Anglais)",
    ],

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////               App

'services' => [
    "specialty_not_found" => "La spécialité saisie n'est pas trouvée dans notre base de données. Veuillez vérifier l'orthographe et réessayer.",
    "name_fr" => "Nom du département (Français)",
    "name_en" => "Nom du département (Anglais)",
    "name_ar" => "Nom du département (Arabe)",
    "tel" => "Téléphone principal",
    "fax" => "Fax",
    "head_service" => "Chef de département",
    "specialty" => "Spécialité médicale",
    'email' => "Email",
    'beds_number' => "Nombre de lits",
    'specialists_number' => "Nombre de spécialistes",
    'physicians_number' => "Nombre de médecins",
    'paramedics_number' => "Nombre de paramédicaux",
],
];
