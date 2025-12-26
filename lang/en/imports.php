<?php
return [
    "line_number_error" => "Error in line number (:number) ",
    'users' => [
        'nom_d_utilisateur' => 'Username',
        'e_mail' => 'Email Address',
    ],
    'persons' => [
        'nom_francais' => 'Last Name (FR)',
        'prenom_francais' => 'First Name (FR)',
        'nom_arabe' => 'Last Name (AR)',
        'prenom_arabe' => 'First Name (AR)',
        'e_mail' => 'Email',
        'compte_bancaire' => 'Account Number',
        'banque' => 'Bank',
    ],

    "banking_information" => [
        "exist" => "account number already Exists",
        'employee' => [
            'name-mismatch' => 'Employee name does not match records. Provided: ":provided", Expected: ":expected".',
        ],
    ],
    'municipalities' => [
        'code' => "Municipality Code",
        'designation_fr' => "Municipality Name (French)",
        'designation_ar' => "Municipality Name (Arabic)",
        'designation_en' => "Municipality Name (English)",
    ],
    'dairates' => [
        'code' => "District Code",
        'designation_fr' => "District Name (French)",
        'designation_ar' => "District Name (Arabic)",
        'designation_en' => "District Name (English)",
    ],



    'wilayates' => [
        "code" => "Province Code",
        "designation_fr" => "Province Name (French)",
        "designation_ar" => "Province Name (Arabic)",
        "designation_en" => "Province Name (English)",
    ],
    'fields' => [
        "acronym" => "Field Code",
        "designation_fr" => "Field Name (French)",
        "designation_ar" => "Field Name (Arabic)",
        "designation_en" => "Field Name (English)",
    ],
    'field_grades' => [
        "acronym" => "Grade Code",
        "designation_fr" => "Grade Title (French)",
        "designation_ar" => "Grade Title (Arabic)",
        "designation_en" => "Grade Title (English)",
    ],
    'field_specialties' => [
        "acronym" => "Specialty Code",
        "designation_fr" => "Specialty Title (French)",
        "designation_ar" => "Specialty Title (Arabic)",
        "designation_en" => "Specialty Title (English)",
    ],

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////               App

    'services' => [
        "specialty_not_found" => "The entered specialty is not found in our database. Please verify the spelling and try again.",
        "name_fr" => "Service Name (French)",
        "name_en" => "Service Name (English)",
        "name_ar" => "Service Name (Arabic)",
        "tel" => "Primary Phone",
        "fax" => "Fax",
        "head_service" => "Head of Service",
        "specialty" => "Medical Specialty",
        'email'=> "Email",
        'beds_number' =>"Beds Number",
        'specialists_number'=> "Specialist Number",
        'physicians_number'=> "Physicians Number",
        'paramedics_number'=> "Paramedics Number",
    ],

];
