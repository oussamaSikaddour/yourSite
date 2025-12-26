<?php

return [
    "common" => [
        "errors" => [
            "lang" => "Veuillez vérifier le segment d'URL suivant 'api/' et assurer qu'il spécifie l'une des langues: fr, ar ou en",
            'deactivated_account' => "Votre compte a été désactivé. Veuillez le réactiver ou contacter votre administrateur",
        ]
    ],
    "responses" => [
        "maintenance" => "L'application est actuellement en maintenance. Veuillez réessayer ultérieurement.",
        "logout" => "Vous avez été déconnecté avec succès.",
        "logout_all_devices" => "Vous avez été déconnecté avec succès de tous vos appareils.",
        'account_activated' => "Votre compte a été activé avec succès. Bon retour parmi nous !",
        'account_deactivated' => "Votre compte a été désactivé avec succès. Nous espérons vous revoir bientôt.",
    ],
    "change_email" => [
        "errors" => [
            "old_email" => "L'email que vous avez saisi ne correspond pas à votre adresse email actuelle.",
            "new_email" => "La nouvelle adresse email doit être différente de l'actuelle."
        ]
    ],
    "users" => [
        "responses" => [
            "bulk_insert_success" => "Toutes les personnes ont été ajoutées avec succès.",
            "destroy" => "Votre compte a été supprimé avec succès."
        ],
        "errors" => [
            "update" => [
                "no-access" => "Vous ne pouvez pas modifier ce compte. Seul le propriétaire du compte peut effectuer cette action."
            ],
            "destroy" => [
                "no-access" => "Vous ne pouvez pas supprimer ce compte. Seul le propriétaire du compte peut effectuer cette action."
            ]
        ]
    ],
    "occupations" => [
        "responses" => [
            "destroy" => "L'occupation a été supprimée avec succès."
        ],
        "errors" => [
            "not-belong" => "L'occupation n'appartient pas à l'utilisateur sélectionné."
        ]
    ],
    "banking_information" => [
        "responses" => [
            "destroy" => "Les informations bancaires ont été supprimées avec succès."
        ],
        "errors" => [
            "not-found" => "Le propriétaire du compte n'a pas pu être trouvé."
        ]
    ],
];
