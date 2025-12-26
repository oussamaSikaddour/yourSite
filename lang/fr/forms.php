<?php


return [
    'common' => [
        'actions' => [
            "confirm" => "Oui",
            "cancel" => "Non",
            'submit' => 'Soumettre',
            'reset' => 'Réinitialiser le formulaire',
        ],
        'errors' => [
            "default" => "Une erreur est survenue. Veuillez contacter votre équipe informatique.",
            "not_match" => [
                'phone' => 'Le numéro de téléphone doit commencer par 05, 06 ou 07 et contenir exactement 10 chiffres.',
                "land_line" => "Le numéro de fixe doit commencer par 0 et contenir exactement 9 chiffres"
            ],
            'img' => [
                'not_img' => 'Le fichier doit être une image.',
                "not_imgs" => "Les fichiers doivent être des images",
            ],
            'user' => [
                'not_exists' => 'Le champ :attribute est obligatoire.',
            ],
        ],
    ],

    'site_parameters' => [
        'steps' => [
            'first' => [
                'password' => 'Mot de passe',
                'email' => 'Votre email',
            ],
            'last' => [
                "maintenance" => "Mode maintenance",
                'state' => "État",
                'enable' => "Activer",
                'disable' => "Désactiver",
            ],
        ],
        "actions" => [
            "download_db" => "Télécharger la base de données"
        ],
        'responses' => [
            'you_can_pass' => 'Vous avez les identifiants pour mettre à jour l\'état de l\'application',
            'success' => 'Vous avez mis à jour avec succès l\'état de l\'application',
        ],
        'errors' => [
            'no_access' => "Vous n'avez pas les identifiants nécessaires pour l'étape suivante",
            'user_not_found' => 'Vérifiez votre email et mot de passe et réessayez',
        ],
    ],
    'login' => [
        'email' => 'Votre email',
        'password' => 'Votre mot de passe',
        'actions' => [
            'submit' => 'Se connecter',
        ],
        'responses' => [
            'success' => 'Vous avez été connecté avec succès.',
        ],
        'errors' => [
            'too_many_attempts' => 'Trop de tentatives de connexion. Veuillez réessayer plus tard.',
            'invalid_credentials' => 'Les identifiants fournis sont invalides.',
        ],
    ],

    'register' => [
        'instructions' => [
            'email' => 'Email valide requis. Un code de vérification sera envoyé.',
            'code' => 'Entrez le code de vérification envoyé à votre email.',
        ],
        'email' => 'Adresse email',
        'password' => 'Mot de passe',
        'code' => 'Code de vérification',
        'actions' => [
            'get_code' => 'Obtenir le code',
            'get_new_code' => 'Renvoyer le code',
            'submit' => 'Créer un compte',
        ],
        'responses' => [
            'new_code' => 'Nouveau code de vérification envoyé à votre email.',
            'success' => 'Compte créé avec succès.',
        ],
        'errors' => [
            'verification_code' => 'Code de vérification invalide ou expiré.',
        ],
    ],
    'forgot_password' => [
        'instructions' => [
            'email' => 'Entrez votre email pour recevoir un code de vérification.',
            'code' => 'Entrez le code envoyé à votre email.',
        ],
        'email' => 'Adresse email',
        'code' => 'Code de vérification',
        'password' => 'Nouveau mot de passe',
        'actions' => [
            'get_code' => 'Envoyer le code',
        ],
        'responses' => [
            'new_code' => 'Nouveau code de vérification envoyé.',
            'success' => 'Mot de passe réinitialisé avec succès.',
        ],
        'errors' => [
            'no_user' => 'Aucun compte trouvé avec cette adresse email.',
            'verification_code' => 'Code de vérification invalide ou expiré.',
        ],
    ],
    'change_password' => [
        'infos' => [
            'redirect' => 'Après avoir changé votre mot de passe, vous serez déconnecté.',
        ],
        'old_pwd' => 'Votre ancien mot de passe',
        'pwd' => 'Votre nouveau mot de passe',
        'responses' => [
            'success' => 'La modification a été effectuée avec succès. Vous allez être déconnecté maintenant.',
        ],
        'errors' => [
            'old_pwd' => 'Veuillez vérifier votre ancien mot de passe et réessayer.',
            'invalid_current' => "La modification du mot de passe est restreinte aux Super Administrateurs et au propriétaire du compte",
        ],
    ],
    'change_mail' => [
        'infos' => [
            'redirect' => 'Vous serez déconnecté après avoir changé votre email.',
        ],
        'pwd' => 'Mot de passe',
        'mail' => 'Email actuel',
        'new_mail' => 'Nouvel email',
        'responses' => [
            'success' => 'Votre email a été changé avec succès. Vous allez maintenant être déconnecté.',
        ],
        'errors' => [
            'auth' => 'Veuillez vérifier votre email actuel et votre mot de passe et réessayer.',
        ],
    ],
    'general_infos' => [
        'inaugural_year' => "Année d'inauguration",
        'email' => "Adresse email",
        'app_name' => "Nom de l'application",
        'acronym' => "Acronyme de l'institution",
        "address_fr" => "Adresse officielle (Français)",
        "address_en" => "Adresse officielle (Anglais)",
        "address_ar" => "Adresse officielle (Arabe)",
        'logo' => "Logo de l'institution",
        'phone' => "Téléphone principal",
        'landline' => "Numéro de fixe",
        'fax' => "Numéro de fax",
        'map' => "Localisation Google Maps",
        'youtube' => "YouTube",
        'facebook' => "Facebook",
        'linkedin' => "LinkedIn",
        'github' => "GitHub",
        'instagram' => "Instagram",
        'tiktok' => "TikTok",
        'twitter' => "Twitter",
        'threads' => "Threads",
        'snapchat' => "Snapchat",
        'pinterest' => "Pinterest",
        'reddit' => "Reddit",
        'telegram' => "Telegram",
        'whatsapp' => "WhatsApp",
        'discord' => "Discord",
        'twitch' => "Twitch",
        'responses' => [
            'success' => 'Les informations générales de l\'application ont été mises à jour avec succès',
        ],
    ],
    'manage_hero' => [
        'title_ar' => "Titre (AR)",
        'title_fr' => "Titre (FR)",
        'title_en' => "Titre (EN)",
        'sub_title_ar' => "Sous-titre (AR)",
        'sub_title_fr' => "Sous-titre (FR)",
        'sub_title_en' => "Sous-titre (EN)",
        "introduction_fr" => "Introduction (FR)",
        "introduction_ar" => "Introduction (AR)",
        "introduction_en" => "Introduction (EN)",
        "primary_call_to_action_fr" => "CTA principal (FR)",
        "primary_call_to_action_ar" => "CTA principal (AR)",
        "primary_call_to_action_en" => "CTA principal (EN)",
        "secondary_call_to_action_fr" => "CTA secondaire (FR)",
        "secondary_call_to_action_ar" => "CTA secondaire (AR)",
        "secondary_call_to_action_en" => "CTA secondaire (EN)",
        "images" => "Images de la page d'accueil",
        'responses' => [
            'success' => 'Le contenu de la page d\'accueil a été mis à jour avec succès',
        ],
    ],
    'manage_about_us' => [
        "sub_title_fr" => "Sous-titre (Fr)",
        "sub_title_ar" => "Sous-titre (Ar)",
        "sub_title_en" => "Sous-titre (En)",

        'first_paragraph_fr' => "Premier paragraphe (Fr)",
        'first_paragraph_ar' => "Premier paragraphe (Ar)",
        'first_paragraph_en' => "Premier paragraphe (En)",
        'second_paragraph_fr' => "Deuxième paragraphe (Fr)",
        'second_paragraph_ar' => "Deuxième paragraphe (Ar)",
        'second_paragraph_en' => "Deuxième paragraphe (En)",
        'third_paragraph_fr' => "Troisième paragraphe (Fr)",
        'third_paragraph_ar' => "Troisième paragraphe (Ar)",
        'third_paragraph_en' => "Troisième paragraphe (En)",

        "image" => "Image de la section À propos",

        'responses' => [
            'success' => 'Vous avez mis à jour avec succès les informations de la page À propos de votre application',
        ],
    ],
    'our_quality' => [
        'name_ar' => "Titre en arabe",
        'name_fr' => "Titre en français",
        'name_en' => "Titre en anglais",
        "image" => "Image",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès une nouvelle qualité',
            'update_success' => 'Vous avez mis à jour avec succès la qualité sélectionnée',
        ],
    ],

    "person" => [
        "last_name_fr" => "Nom de famille (FR)",
        "last_name_ar" => "Nom de famille (AR)",
        "first_name_fr" => "Prénom (FR)",
        "first_name_ar" => "Prénom (AR)",
        "profile_img" => "Photo de profil",
        'is_paid' => 'Statut de paiement',
        'is_active' => 'Statut du compte',
        "cv" => "Document CV",
        "email" => "Email",
        "card_number" => "Numéro national d'identité",
        "birth_date" => "Date de naissance",
        'birth_place_fr' => "Lieu de naissance (FR)",
        'birth_place_ar' => "Lieu de naissance (AR)",
        "address_fr" => "Adresse (FR)",
        "address_ar" => "Adresse (AR)",
        "address_en" => "Adresse (EN)",
        'phone' => "Téléphone",
        "employee_number" => "Identifiant employé",
        "social_number" => "Numéro de sécurité sociale",
        'responses' => [
            "add_success" => "Dossier personnel créé avec succès",
            "update_success" => "Dossier personnel mis à jour : :name",
        ],
    ],
    "user" => [
        'instructions' => [
            "email" => "Email valide requis. Un code de vérification sera envoyé à cette adresse.",
        ],
        'email' => "Email",
        "name" => "Nom d'utilisateur",
        'is_active' => 'Statut du compte',
        "password" => "Mot de passe",
        "person_id" => "Personnel",
        "avatar" => "Avatar",
        'responses' => [
            "add_success" => "Compte utilisateur créé avec succès",
            "update_success" => "Compte utilisateur mis à jour : :name",
        ],
    ],

    'role' => [
        'user_id' => "Compte utilisateur",
        'roles' => "Rôles utilisateur",
        'errors' => [
            'user_id_required' => 'La sélection de l\'utilisateur est requise',
            'user_id_exists'   => 'Le compte utilisateur spécifié n\'existe pas',
            'roles_required'   => 'Au moins un rôle doit être sélectionné',
            'roles_array'      => 'Les rôles doivent être fournis comme identifiants valides',
            'roles_exist'      => 'Un ou plusieurs rôles spécifiés sont invalides',
            'user_not_found'   => 'Le compte utilisateur demandé n\'a pas été trouvé',
            'error_title'      => 'Erreur d\'attribution des rôles',
        ],
        'responses' => [
            'success'      => 'Les rôles utilisateur ont été mis à jour avec succès',
            'own_success'  => 'Vos rôles ont été mis à jour. Pour des raisons de sécurité, vous avez été déconnecté de toutes les sessions.',
        ],
    ],

    "wilaya" => [
        'designation_fr' => "Nom français",
        'designation_ar' => "Nom arabe",
        'designation_en' => "Nom anglais",
        'code' => "Code de la wilaya",
        'responses' => [
            'add_success' => 'Wilaya créée avec succès',
            'update_success' => 'Wilaya mise à jour avec succès',
        ],
    ],
    "daira" => [
        'designation_fr' => "Nom français",
        'designation_ar' => "Nom arabe",
        'designation_en' => "Nom anglais",
        'code' => "Code de la daïra",
        'responses' => [
            'add_success' => 'Daïra créée avec succès',
            'update_success' => 'Daïra mise à jour avec succès',
        ],
    ],
    "commune" => [
        'designation_fr' => "Nom français",
        'designation_ar' => "Nom arabe",
        'designation_en' => "Nom anglais",
        'code' => "Code de la commune",
        'responses' => [
            'add_success' => 'Commune créée avec succès',
            'update_success' => 'Commune mise à jour avec succès',
        ],
    ],
    "field" => [
        'designation_fr' => "Désignation française",
        'designation_ar' => "Désignation arabe",
        'designation_en' => "Désignation anglaise",
        'acronym' => "Acronyme",
        'responses' => [
            'add_success' => 'Domaine professionnel créé avec succès',
            'update_success' => 'Domaine mis à jour avec succès',
        ],
    ],
    "field_grade" => [
        'designation_fr' => "Désignation française",
        'designation_ar' => "Désignation arabe",
        'designation_en' => "Désignation anglaise",
        'acronym' => "Code du grade",
        'field_id' => "Domaine professionnel",
        'responses' => [
            'add_success' => 'Niveau de grade créé avec succès',
            'update_success' => 'Niveau de grade mis à jour avec succès',
        ],
    ],
    "field_specialty" => [
        'designation_fr' => "Désignation française",
        'designation_ar' => "Désignation arabe",
        'designation_en' => "Désignation anglaise",
        'acronym' => "Code de spécialité",
        'field_id' => "Domaine professionnel",
        'responses' => [
            'add_success' => 'Spécialité professionnelle créée avec succès',
            'update_success' => 'Spécialité mise à jour avec succès',
        ],
    ],
    "occupation" => [
        'field_id' => "Domaine professionnel",
        'field_specialty_id' => "Domaine de spécialisation",
        'field_grade_id' => "Grade professionnel",
        "description_fr" => "Description professionnelle (Français)",
        "description_en" => "Description professionnelle (Anglais)",
        "description_ar" => "Description professionnelle (Arabe)",
        "experience" => "Années d'expérience professionnelle",
        'errors' => [
            'field_required' => 'La sélection du domaine professionnel est requise',
            'field_exists' => 'Le domaine professionnel sélectionné est invalide',
            'field_specialty_exists' => 'Le domaine de spécialisation sélectionné est invalide',
            'field_grade_exists' => 'Le grade professionnel sélectionné est invalide',
        ],
        'responses' => [
            'add_success' => 'L\'occupation professionnelle a été ajoutée avec succès',
            'update_success' => 'L\'occupation professionnelle a été mise à jour avec succès',
        ],
    ],
    "banking_information" => [
        "agency_fr" => "Agence bancaire (Français)",
        "agency_ar" => "Agence bancaire (Arabe)",
        "agency_en" => "Agence bancaire (Anglais)",
        "agency_code" => "Code d'agence",
        "account_number" => "Numéro de compte",
        "bank_id" => "Institution financière",
        'errors' => [
            'bankable_id_required' => 'L\'identifiant de l\'entité associée est requis',
            'bankable_type_required' => 'Le type d\'entité associée est requis',
            'bankable_type_invalid' => 'Le type d\'entité spécifié est invalide',
        ],
        'responses' => [
            'add_success' => 'Les informations bancaires ont été ajoutées avec succès',
            'update_success' => 'Les informations bancaires ont été mises à jour avec succès',
        ],
    ],
    "bank" => [
        "acronym" => "Acronyme",
        "designation_ar" => "Désignation en arabe",
        "designation_fr" => "Désignation en français",
        "designation_en" => "Désignation en anglais",
        "code" => "Code",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès une nouvelle banque',
            'update_success' => 'Vous avez mis à jour avec succès la banque sélectionnée',
        ],
    ],
    "guest" => [
        "message" => [
            'name' => "Nom",
            'name-placeholder' => "Votre nom",
            'email' => "Email",
            'email-placeholder' => "Votre email",
            "message" => "Message",
            'message-placeholder' => "Votre message",
            'responses' => [
                'send_success' => 'Votre message a été envoyé avec succès. Une réponse vous sera envoyée à votre adresse email',
            ],
        ]
    ],
    "article" => [
        'title_fr' => "Titre en français",
        'title_ar' => "Titre en arabe",
        'title_en' => "Titre en anglais",
        "content_fr" => "Contenu en français",
        "content_en" => "Contenu en anglais",
        "content_ar" => "Contenu en arabe",
        "published_at" => "Publié le",
        "articleable_type" => "Type de publication",
        "articleable_id" => "Publié dans",
        "images" => "Images",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès un nouvel article',
            'update_success' => 'Vous avez mis à jour avec succès l\'article sélectionné',
        ],
    ],

    "external_link" => [
        "name_fr" => "Nom en français",
        "name_ar" => "Nom en arabe",
        "name_en" => "Nom en anglais",
        'url' => "URL",
        "menu_id" => "Nom du menu",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès un nouveau lien externe',
            'update_success' => 'Vous avez mis à jour avec succès le lien externe sélectionné',
        ],
    ],

    "menu" => [
        'title_fr' => "Titre en français",
        'title_ar' => "Titre en arabe",
        'title_en' => "Titre en anglais",
        "type" => "Type",
        "state" => "État",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès un nouveau menu',
            'update_success' => 'Vous avez mis à jour avec succès le menu sélectionné',
        ],
    ],

    "slide" => [
        'title_fr' => "Titre en français",
        'title_ar' => "Titre en arabe",
        'title_en' => "Titre en anglais",
        "content_fr" => "Contenu en français",
        "content_en" => "Contenu en anglais",
        "content_ar" => "Contenu en arabe",
        "order" => "Ordre de la diapositive",
        "slider_id" => "Diaporama",
        'image' => "Image",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès une nouvelle diapositive',
            'update_success' => 'Vous avez mis à jour avec succès la diapositive sélectionnée',
        ],
    ],
    "slider" => [
        "name" => "Titre de la diapositive",
        "position" => "Position d'affichage",
        "user_id" => "Publié par",
        'state' => "Statut de publication",
        'responses' => [
            'add_success' => 'Nouvelle diapositive ajoutée avec succès',
            'update_success' => 'Diapositive sélectionnée mise à jour avec succès',
        ],
    ],
    "trend" => [
        'title_fr' => "Titre en français",
        'title_ar' => "Titre en arabe",
        'title_en' => "Titre en anglais",
        "content_fr" => "Contenu en français",
        "content_en" => "Contenu en anglais",
        "content_ar" => "Contenu en arabe",
        "start_at" => "À partir du",
        "end_at" => "Jusqu'au",
        'responses' => [
            'add_success' => 'Vous avez ajouté avec succès une nouvelle tendance',
            'update_success' => 'Vous avez mis à jour avec succès la tendance sélectionnée',
        ],
    ],

    'image' => [
        'display_name' => "Nom d'affichage",
        'use_case' => "Cas d'utilisation",
        'real_image' => "Fichier image",
        'responses' => [
            "add_success" => "Fichier image ajouté avec succès",
            'update_success' => "Fichier image mis à jour avec succès",
        ],
    ],

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////               App
'service' => [
    "name_fr" => "Nom du département (Fr)",
    "name_ar" => "Nom du département (Ar)",
    "name_en" => "Nom du département (En)",
    "introduction_fr" => "Introduction (Fr)",
    "introduction_ar" => "Introduction (Ar)",
    "introduction_en" => "Introduction (En)",
    "specialty" => "Spécialité médicale",
    "tel" => "Téléphone principal",
    "fax" => "Fax",
    'email' => "Email",
    'beds_number' => "Nombre de lits",
    'specialists_number' => "Nombre de spécialistes",
    'physicians_number' => "Nombre de médecins",
    'paramedics_number' => "Nombre de paramédicaux",
    "head_of_service_id" => "Chef de département",
    "establishment_id" => "Établissement affilié",

    'responses' => [
        'add_success' => "Département hospitalier créé avec succès",
        'update_success' => "Département mis à jour avec succès",
    ],
],
    "global_transfer" => [
        'number' => "Référence du transfert",
        'date' => 'Date du transfert',
        'motive_ar' => "Motif (Arabe)",
        'motive_fr' => "Motif (Français)",
        'motive_en' => "Motif (Anglais)",
        'user_id' => "Administrateur de la plateforme",
        'responses' => [
            'add_success' => 'Transfert global créé avec succès',
            'update_success' => 'Transfert global mis à jour avec succès',
        ],
    ],

    "transfer" => [
        'amount' => "Montant",
        'user_id' => "Bénéficiaire",
        'global_bank_transfer_id' => "Transfert global",
        'responses' => [
            'add_success' => 'Transfert créé avec succès',
            'update_success' => 'Transfert mis à jour avec succès',
        ],
    ],

    "bonus" => [
        "amount" => "Montant",
        "titled_ar" => "Titre (Arabe)",
        "titled_fr" => "Titre (Français)",
        "titled_en" => "Titre (Anglais)",
        'responses' => [
            'add_success' => 'Bonus créé avec succès',
            'update_success' => 'Bonus mis à jour avec succès',
        ],
    ],
];
