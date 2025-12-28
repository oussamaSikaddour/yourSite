<?php
return [
    "banking_information" => [
        "account" => [
            "length" => "For successful validation, your account number must consist of exactly 20 numerical characters.",
            "check" => "Account Number Verification Failed.The provided key was: :providedKey .
                                 The correct key is: :ribKey .",
            "exists_not_active" => "The account number :account is currently inactive. Please ensure the account is activated before proceeding.",
            "not_exist" => "No active banking information exists for this account. You need  valid and active bank account to continue.",
        ]
    ],

    "land_line" => [
        "invalid" => "The selected :number must not equal an existing land line number , or a fax number"
    ],
    "daira" => [
        "invalid" => "The ':name' Daira must be valid and exist within your Wilaya. Please verify the name or contact your administrator."
    ]

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           App

];
