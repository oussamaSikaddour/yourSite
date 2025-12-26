<?php
return [
    "common" => [
        "excel-file-type-err" => "The file must be in Excel format (XLSX, XLS, CSV)",
        "actions" => "Actions",
        "perPage" => "Per Page"
    ],


    'images' => [
        "info" => "images files list",
        "not_found" => "No Images Files found",
        'display_name' => "Display Name",
        "use_case" => "Use Case",
        'created_at' => "Added At",
        'preview' => "Preview",
    ],
    'files' => [
        "info" => "Pdfs files list",
        "not_found" => "No Pdfs Files found",
        'display_name' => "Display Name",
        "use_case" => "Use Case",
        'created_at' => "Added At",
        'preview' => "Preview",
        "download" => "Download File"
    ],

    'users' => [
        "info" => 'User Registry',

        "not_found" => "No users available",
        "name" => "Username",
        "email" => "Email Address",
        "avatar" => "Profile",
        "registration_date" => "Compte créé le",

        "excel" => [
            "upload" => [
                "success" => "Users imported successfully"
            ]
        ]
    ],
    'persons' => [
        "info" => "Personnel Registry",
        "empty" =>  "No personnel records found",
        "full_name" => "Full Name",
        "full_name_fr" => "Full Name (FR)",
        "full_name_ar" => "Full Name (AR)",
        "employee_number" => "Employee ID",
        "social_number" => "Social Security No.",
        "email" => "Official Email",
        "registration_date" => "Registration Date",
        "phone" => "Phone",
        "card_number" => "National ID",
        "birth_date" => "Birth Date",
        "birth_place_fr" => "Birth Place (FR)",
        "birth_place_ar" => "Birth Place (AR)",
        "birth_place_en" => "Birth Place (EN)",
        "excel" => [
            "upload" => [
                "success" => "Personnel records imported successfully"
            ]
        ]
    ],
    'our_qualities' => [
        'info' => 'Our Qualities List',
        'not_found' => 'No qualities found at the moment', // Improved wording
        'created_at' => 'Added Date',
        'name' => 'Name', // More concise
        'status' => 'Status',
        'errors' => [
            'active_limit' => 'Only 4 qualities can be active to be shown to website visitors', // Improved wording
        ],
    ],
    'messages' => [
        'info' => 'Visitors\' Messages',
        'not_found' => 'No visitors\' messages found at the moment', // Improved wording and possessive
        'name' => 'Name', // More concise
        'email' => 'Email',
        'created_at' => 'Received Date',
    ],

    'wilayates' => [
        "info" => "Wilayates List",
        "not_found" => "No Wilayates Fond at the moment",
        "code" => "code",
        "designation" => "Designation",
        "designation_fr" => "Designation (French)",
        "designation_ar" => "Designation (Arabic)",
        "designation_en" => "Designation (English)",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "Wilayates list uploaded successfully"
            ]
        ]
    ],
    'wilayates' => [
        "info" => "States Directory",
        "not_found" => "No states currently available",
        "code" => "State Code",
        "designation" => "State Name",
        "designation_fr" => "French Name",
        "designation_ar" => "Arabic Name",
        "designation_en" => "English Name",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "States data imported successfully"
            ]
        ]
    ],
    'dairates' => [
        "info" => "Districts of State (Code: :code)",
        "not_found" => "No districts currently available",
        "code" => "District Code",
        "designation" => "District Name",
        "designation_fr" => "French Name",
        "designation_ar" => "Arabic Name",
        "designation_en" => "English Name",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "Districts data imported successfully"
            ]
        ]
    ],
    'communes' => [
        "info" => "Municipalities of District (Code: :code)",
        "not_found" => "No municipalities currently available",
        "code" => "Municipality Code",
        "designation" => "Municipality Name",
        "designation_fr" => "French Name",
        "designation_ar" => "Arabic Name",
        "designation_en" => "English Name",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "Municipalities data imported successfully"
            ]
        ]
    ],
    'fields' => [
        "info" => "Fields List",
        "not_found" => "No Fields Fond at the moment",
        "acronym" => "Acronym",
        "designation" => "Designation",
        "designation_fr" => "Designation (French)",
        "designation_ar" => "Designation (Arabic)",
        "designation_en" => "Designation (English)",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "Fields list uploaded successfully"
            ]
        ]
    ],
    'field_grades' => [
        "info" => "Grade Levels for Field: :acronym",
        "not_found" => "No grade levels currently available",
        "acronym" => "Grade Code",
        "designation" => "Grade Title",
        "designation_fr" => "French Title",
        "designation_ar" => "Arabic Title",
        "designation_en" => "English Title",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "Grade levels imported successfully"
            ]
        ]
    ],
    'field_specialties' => [
        "info" => "Professional Specialties: :acronym",
        "not_found" => "No specialties currently available",
        "acronym" => "Specialty Code",
        "designation" => "Specialization Title",
        "designation_fr" => "French Title",
        "designation_ar" => "Arabic Title",
        "designation_en" => "English Title",
        "registration_date" => "Registration Date",
        "excel" => [
            "upload" => [
                "success" => "Specializations imported successfully"
            ]
        ]
    ],

    'occupations' => [
        "info" => "Occupations List",
        "info_custom" => ":name 's Occupations List",
        "not_found" => "No Occupations Fond at the moment",
        "is_active" => "State",
        "entitled" => "Entitled",
        "field" => "Field",
        "experience" => "Experience",
        "specialty" => "Specialty",
        "grade" => "Grade",
        "created_at" => "Added At",
    ],
    'banking_information' => [
        "info" => "Banking information List",
        "info_custom" => ":name 's Banking information",
        "not_found" => "No Banking information Fond at the moment",
        "bank_acronym" => "Bank",
        "agency" => "Agency",
        "agency_code" => "Agency code",
        "account_number" => "Account Number",
        "is_active" => "State",
        "created_at" => "Added At",
    ],

    'banks' => [
        "info" => "Bank Directory",
        "not_found" => "No banks currently available",
        'code' => "Bank Code",
        'acronym' => "Bank Acronym",
        "designation" => "Bank Name",
        "designation_fr" => "French Name",
        "designation_ar" => "Arabic Name",
        "designation_en" => "English Name",
        "created_at" => "Registration Date",
    ],
    'menus' => [
        "info" => "Menus List",
        "not_found" => "No Menus Fond at the moment",
        "title" => "Title",
        "state" => "State",
        "type" => "Type",
        "created_at" => "Added At",
    ],
    'external_links' => [
        "info" => "External Links List",
        "not_found" => "No External Links Fond at the moment",
        "name" => "Name",
        "url" => "Url",
        "created_at" => "Added At",
    ],
    'articles' => [
        "info" => "Articles List",
        "not_found" => "No Articles Fond at the moment",
        "created_at" => "Added At",
        'author' => "Author",
        'title' => "Title",
        "articleable_type" => "Published For",
        "articleable_id" => "Published In",
        "location" => "Location",
        "state" => "State",

    ],
    'sliders' => [
        "info" => "Sliders List",
        "not_found" => "No Sliders Found",
        "created_at" => "Added At",
        'creator' => "Creator",
        'name' => "Name",
        "position" => "Position",
        "location" => "Location",
        "state" => "Status",
    ],
    "slides" => [
        "info" => "Slider List",
        "not_found" => "No Slider Fond at the moment",
        "created_at" => "Added At",
        'title' => "Title",
        'order' => 'Order',
        'image' => "Image",
        "location" => "Location",
        "state" => "State",
    ],
    "trends" => [
        "info" => "Trends List",
        "not_found" => "No Trends Fond at the moment",
        "created_at" => "Added At",
        'title' => "Title",
        "state" => "State",
    ],



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           App

    'services' => [
        "info" => "Services List for Establishment",
        "not_found" => "No services currently registered",
        "created_at" => "Registration Date",
        "name" => "Service Name",
        "name_fr" => "Service Name (French)",
        "name_en" => "Service Name (English)",
        "name_ar" => "Service Name (Arabic)",
        "tel" => "Primary Phone",
        "fax" => "Fax",
        'email' => "Email",
        'beds_number' => "Beds Number",
        'specialists_number' => "Specialist Number",
        'physicians_number' => "Physicians Number",
        'paramedics_number' => "Paramedics Number",
        "head_service" => "Head of Service",
        "specialty" => "Medical Specialty",
        "excel" => [
            "upload" => [
                "success" => "Services imported successfully"
            ]
        ]
    ],

    'global_transfers' => [
        "info" => "Global Transfers Overview",
        "not_found" => "No global transfers available",
        "initiator" => "Initiator",
        "motive" => "Motive",
        "total_amount" => "Total Amount",
        "reference" => "Reference",
        'date' => "Transfer Date",
        'number' => "Transfer Number",
        'date_min' => "From Date",
        'date_max' => "To Date",
        "created_at" => "Creation Date",
    ],


    'bonuses' => [
        "info" => "Bonus List",
        "not_found" => "No bonuses available",
        "titled" => "Title",
        "amount" => "Amount",
        "created_at" => "Created Date",
    ],
    'transfers' => [
        "info" => "Transfer List - :motive",
        "not_found" => "No transfers available",
        "beneficiary" => "Beneficiary",
        "bank" => "Bank",
        'account' => "Account Number",
        'amount' => "Amount",
        "excel" => [
            "upload-success" => "Excel file processed successfully"
        ],
        "errors" => [
            "bonuses" => [
                "not_selected" => "No bonuses selected",
                "not_set" => "Please select bonuses from the table above",
                "empty" => "Add transfers before assigning bonuses"
            ],
            "not_found" => [
                "active_establishment_banking_info" => "Please add banking information to the establishment or activate existing details"
            ]
        ]
    ],
];
