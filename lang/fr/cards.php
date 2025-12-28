<?php

return [

    'service_details' => [

        'head_of_service' => 'Chef de département',
        'overview'        => 'Aperçu du département',

        'beds'            => 'Lits',
        'specialists'     => 'Spécialistes',
        'physicians'      => 'Médecins',
        'paramedics'      => 'Paramédicaux',

    ],
    "article" => [
        'extend' => "Lire la suite",
        'less' => "Voir moins"
    ],

    'error' => [
        '403' => [
            'title' => "Accès refusé",
            'text' => "Vous n'avez pas la permission d'accéder à cette page."
        ],
        '404' => [
            'title' => "Page non trouvée",
            "text" => "La page que vous recherchez n'existe pas ou a été déplacée."
        ],
        '419' => [
            'title' => 'Session expirée',
            'text' => 'Veuillez rafraîchir la page ou vous reconnecter.'
        ],
        '429' => [
            'title' => 'Trop de requêtes',
            'text' => 'Vous effectuez des requêtes trop rapidement. Veuillez ralentir.'
        ],
        '500' => [
            'title' => 'Erreur serveur',
            'text' => 'Une erreur est survenue de notre côté. Veuillez réessayer plus tard.'
        ],
        "503" => [
            "title" => 'En maintenance',
            'text' => 'Nous effectuons des mises à jour. Veuillez réessayer bientôt.'
        ]
    ],

];
