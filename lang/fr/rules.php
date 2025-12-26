<?php

return [
    "banking_information" => [
        "account" => [
            "length" => "Pour une validation réussie, votre numéro de compte doit comporter exactement 20 caractères numériques.",
            "check" => "Vérification du numéro de compte échouée. La clé fournie était : :providedKey.
                                 La clé correcte est : :ribKey.",
            "exists_not_active" => "Le numéro de compte :account est actuellement inactif. Veuillez vous assurer que le compte est activé avant de continuer.",
            "not_exist" => "Aucune information bancaire active n'existe pour ce compte. Vous avez besoin d'un compte bancaire valide et actif pour continuer.",
        ]
    ],

    "land_line" => [
        "invalid" => "Ce numéro de fixe :number existe déjà ou correspond à un numéro de fax enregistré"
    ],
    "daira" => [
        "invalid" => "La daïra ':name' doit être valide et exister dans votre wilaya. Veuillez vérifier le nom ou contacter votre administrateur."
    ]

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           App

];
