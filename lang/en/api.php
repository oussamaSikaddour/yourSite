<?php

return [
    "common" => [
        "errors" => [
            "lang" => "Please verify the URL segment following 'api/' and ensure it specifies one of: fr, ar, or en",
            'deactivated_account' => "Your account has been deactivated. Please reactivate it or contact your administrator",
        ]
    ],
    "responses" => [
        "maintenance" => "The application is currently undergoing maintenance. Please check back later.",
        "logout" => "You have been successfully logged out.",
        "logout_all_devices" => "You have been successfully logged out from all your devices.",
        'account_activated' => "Your account has been successfully activated. Welcome back!",
        'account_deactivated' => "Your account has been successfully deactivated. We look forward to your return.",
    ],
    "change_email" => [
        "errors" => [
            "old_email" => "The email you entered doesn't match your current email address.",
            "new_email" => "The new email address must be different from your current one."
        ]
    ],
    "users" => [
        "responses" => [
            "bulk_insert_success" => "All persons have been added successfully.",
            "destroy" => "Your account has been successfully deleted."
        ],
        "errors" => [
            "update" => [
                "no-access" => "You cannot Update this account. Only the account owner can perform this action."
            ],
            "destroy" => [
                "no-access" => "You cannot delete this account. Only the account owner can perform this action."
            ]
        ]
    ],
    "occupations" => [
        "responses" => [
            "destroy" => "The occupation has been deleted successfully."
        ],
        "errors" => [
            "not-belong" => "The occupation does not belong to the selected user."
        ]
    ],
    "banking_information" => [
        "responses" => [
            "destroy" => "The banking information has been deleted successfully."
        ],
        "errors" => [
            "not-found" => "The account owner could not be found."
        ]
    ],
];
