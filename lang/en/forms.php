<?php

return [
    'common' => [
        'actions' => [
            "confirm" => "Yes",
            "cancel" => "No",
            'submit' => 'Submit',
            'reset' => 'Reset Form',
        ],
        'errors' => [
            "default" => "An error occurred. Please contact your IT team.", // More professional and informative
            "not_match" => [
                'phone' => 'Phone number must start with 05, 06, or 07 and contain exactly 10 digits.',
                "land_line" => "The Landline Number must start with 0 and contain exactly 9 digits"
            ],
            'img' => [
                'not_img' => 'The file must be an image.',
                "not_imgs" => "The files must be images",
            ],
            'user' => [
                'not_exists' => 'The :attribute field is required.',
            ],
        ],
    ],

    'site_parameters' => [
        'steps' => [
            'first' => [
                'password' => 'Password',
                'email' => 'Your Email',
            ],
            'last' => [
                "maintenance" => "Maintenance Mode",
                'state' => "State",
                'enable' => "Enable",
                'disable' => "Disable",
            ],
        ],
        "actions" => [
            "download_db" => "Download Database"
        ],
        'responses' => [
            'you_can_pass' => 'You have the credentials to Update the App State', // Corrected grammar
            'success' => 'You have successfully updated the App State',
        ],
        'errors' => [
            'no_access' => "You don't have the necessary credentials for the next Step", // Corrected spelling
            'user_not_found' => 'Check your email and password and try again', // Corrected spelling
        ],
    ],
    'login' => [
        'email' => 'Your Email',
        'password' => 'Your Password',
        'actions' => [
            'submit' => 'Sign In',
        ],
        'responses' => [
            'success' => 'You have been successfully logged in.',
        ],
        'errors' => [
            'too_many_attempts' => 'Too many login attempts. Please try again later.',
            'invalid_credentials' => 'The provided credentials are invalid.',
        ],
    ],

    'register' => [
        'instructions' => [
            'email' => 'Valid email required. Verification code will be sent.',
            'code' => 'Enter the verification code sent to your email.',
        ],
        'email' => 'Email Address',
        'password' => 'Password',
        'code' => 'Verification Code',
        'actions' => [
            'get_code' => 'Get Code',
            'get_new_code' => 'Resend Code',
            'submit' => 'Create Account',
        ],
        'responses' => [
            'new_code' => 'New verification code sent to your email.',
            'success' => 'Account created successfully.',
        ],
        'errors' => [
            'verification_code' => 'Invalid or expired verification code.',
        ],
    ],
    'forgot_password' => [
        'instructions' => [
            'email' => 'Enter your email to receive a verification code.',
            'code' => 'Enter the code sent to your email.',
        ],
        'email' => 'Email Address',
        'code' => 'Verification Code',
        'password' => 'New Password',
        'actions' => [
            'get_code' => 'Send Code',
        ],
        'responses' => [
            'new_code' => 'New verification code sent.',
            'success' => 'Password reset successfully.',
        ],
        'errors' => [
            'no_user' => 'No account found with this email address.',
            'verification_code' => 'Invalid or expired verification code.',
        ],
    ],
    'change_password' => [
        'infos' => [
            'redirect' => 'After changing your password, you will be logged out.',
        ],
        'old_pwd' => 'Your Old Password',
        'pwd' => 'Your New Password',
        'responses' => [
            'success' => 'The change was successful. You will be logged out now.', // Corrected spelling
        ],
        'errors' => [
            'old_pwd' => 'Please check your old password and try again.', // Improved error message
            'invalid_current' => "Password modification is restricted to Super Administrators and the account owner",
        ],
    ],
    'change_mail' => [
        'infos' => [
            'redirect' => 'You will be logged out after changing your email.', // More concise and natural
        ],
        'pwd' => 'Password',
        'mail' => 'Current Email', // More consistent capitalization
        'new_mail' => 'New Email',
        'responses' => [
            'success' => 'Your email has been successfully changed. You will now be logged out.', // More user-friendly
        ],
        'errors' => [
            'auth' => 'Please verify your current email and password and try again.', // More precise and professional
        ],
    ],
    'general_infos' => [
        'inaugural_year'=>"Inaugural Year",
        'email' => "Email Address",
        'app_name' => "Application Name",
        'acronym' => "Institution Acronym",
        "address_fr" => "Official Address (French)",
        "address_en" => "Official Address (English)",
        "address_ar" => "Official Address (Arabic)",
        'logo' => "Institution Logo",
        'phone' => "Primary Phone",
        'landline' => "Landline Number",
        'fax' => "Fax Number",
        'map' => "Google Maps Location",
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
            'success' => 'Application general information has been successfully updated',
        ],
    ],
    'manage_hero' => [
        'title_ar' => "Title (AR)",
        'title_fr' => "Title (FR)",
        'title_en' => "Title (EN)",
        'sub_title_ar' => "Subtitle (AR)",
        'sub_title_fr' => "Subtitle (FR)",
        'sub_title_en' => "Subtitle (EN)",
        "introduction_fr" => "Introduction (FR)",
        "introduction_ar" => "Introduction (AR)",
        "introduction_en" => "Introduction (EN)",
        "primary_call_to_action_fr" => "Primary CTA (FR)",
        "primary_call_to_action_ar" => "Primary CTA (AR)",
        "primary_call_to_action_en" => "Primary CTA (EN)",
        "secondary_call_to_action_fr" => "Secondary CTA (FR)",
        "secondary_call_to_action_ar" => "Secondary CTA (AR)",
        "secondary_call_to_action_en" => "Secondary CTA (EN)",
        "images" => "Hero Images",
        'responses' => [
            'success' => 'Hero page content has been successfully updated',
        ],
    ],
    'manage_about_us' => [

        "sub_title_fr"=>"sub_title (Fr)",
        "sub_title_ar"=>"sub_title (Ar)",
        "sub_title_en"=>"sub_title (En)",

         'first_paragraph_fr'=>"First Paragraph (Fr)",
         'first_paragraph_ar'=>"First Paragraph (Ar)",
         'first_paragraph_en'=>"First Paragraph (En)",
         'second_paragraph_fr'=>"Second Paragraph (Fr)",
         'second_paragraph_ar'=>"Second Paragraph (Ar)",
         'second_paragraph_en'=>"Second Paragraph (En)",
         'third_paragraph_fr'=>"Third Paragraph (Fr)",
         'third_paragraph_ar'=>"Third Paragraph (Ar)",
         'third_paragraph_en'=>"Third Paragraph (En)",

        "image" => "About Us Section Image",

        'responses' => [
            'success' => 'You have successfully updated the AboutUs Page information of your App', // Corrected spelling
        ],

    ],
    'our_quality' => [

        'name_ar' => "Title in Arab",
        'name_fr' => "Title in french",
        'name_en' => "Title in english",
        "image" => "Image",
        'responses' => [
            'add_success' => 'You have successfully added a New Quality', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Quality', // Corrected spelling
        ],

    ],

    "person" => [
        "last_name_fr" => "Last Name (FR)",
        "last_name_ar" => "Last Name (AR)",
        "first_name_fr" => "First Name (FR)",
        "first_name_ar" => "First Name (AR)",
        "profile_img" => "Profile Photo",
        'is_paid' => 'Payment Status',
        'is_active' => 'Account Status',
        "cv" => "CV Document",
        "email" => "Email",
        "card_number" => "National ID",
        "birth_date" => "Birth Date",
        'birth_place_fr' => "Birth Place (FR)",
        'birth_place_ar' => "Birth Place (AR)",
        "address_fr" => "Address (FR)",
        "address_ar" => "Address (AR)",
        "address_en" => "Address (EN)",
        'phone' => "Phone",
        "employee_number" => "Employee ID",
        "social_number" => "Social Security No.",
        'responses' => [
            "add_success" => "Personnel record created successfully",
            "update_success" => "Personnel record updated: :name",
        ],
    ],
    "user" => [
        'instructions' => [
            "email" => "Valid email required. Verification code will be sent to this address.",
        ],
        'email' => "Email",
        "name" => "Username",
        'is_active' => 'Account Status',
        "password" => "Password",
        "person_id" => "Personnel",
        "avatar" => "Avatar",
        'responses' => [
            "add_success" => "User account created successfully",
            "update_success" => "User account updated: :name",
        ],
    ],

    'role' => [
        'user_id' => "User Account",
        'roles' => "User Roles",
        'errors' => [
            'user_id_required' => 'User selection is required',
            'user_id_exists'   => 'The specified user account does not exist',
            'roles_required'   => 'At least one role must be selected',
            'roles_array'      => 'Roles must be provided as valid identifiers',
            'roles_exist'      => 'One or more specified roles are invalid',
            'user_not_found'   => 'The requested user account was not found',
            'error_title'      => 'Role Assignment Error',
        ],
        'responses' => [
            'success'      => 'User roles have been successfully updated',
            'own_success'  => 'Your roles have been updated. For security purposes, you have been logged out of all sessions.',
        ],
    ],

    "wilaya" => [
        'designation_fr' => "French Name",
        'designation_ar' => "Arabic Name",
        'designation_en' => "English Name",
        'code' => "State Code",
        'responses' => [
            'add_success' => 'State created successfully',
            'update_success' => 'State updated successfully',
        ],
    ],
    "daira" => [
        'designation_fr' => "French Name",
        'designation_ar' => "Arabic Name",
        'designation_en' => "English Name",
        'code' => "District Code",  // Changed from "Diara Code"
        'responses' => [
            'add_success' => 'District created successfully',
            'update_success' => 'District updated successfully',
        ],
    ],
    "commune" => [
        'designation_fr' => "French Name",
        'designation_ar' => "Arabic Name",
        'designation_en' => "English Name",
        'code' => "Municipality Code",  // More formal than "Commune Code"
        'responses' => [
            'add_success' => 'Municipality created successfully',
            'update_success' => 'Municipality updated successfully',
        ],
    ],
    "field" => [
        'designation_fr' => "French Designation",
        'designation_ar' => "Arabic Designation",
        'designation_en' => "English Designation",
        'acronym' => "Acronym",
        'responses' => [
            'add_success' => 'Professional field created successfully',
            'update_success' => 'Field updated successfully',
        ],
    ],
    "field_grade" => [
        'designation_fr' => "French Designation",
        'designation_ar' => "Arabic Designation",
        'designation_en' => "English Designation",
        'acronym' => "Grade Code",
        'field_id' => "Professional Field",  // Fixed typo: 'filed_id' → 'field_id'
        'responses' => [
            'add_success' => 'Grade level created successfully',
            'update_success' => 'Grade level updated successfully',
        ],
    ],
    "field_specialty" => [
        'designation_fr' => "French Designation",
        'designation_ar' => "Arabic Designation",
        'designation_en' => "English Designation",
        'acronym' => "Specialty Code",
        'field_id' => "Professional Field",
        'responses' => [
            'add_success' => 'Professional specialty created successfully',
            'update_success' => 'Specialty updated successfully',
        ],
    ],
    "occupation" => [
        'field_id' => "Professional Field",
        'field_specialty_id' => "Area of Specialization",
        'field_grade_id' => "Professional Grade",
        "description_fr" => "Professional Description (French)",
        "description_en" => "Professional Description (English)",
        "description_ar" => "Professional Description (Arabic)",
        "experience" => "Years of Professional Experience",
        'errors' => [
            'field_required' => 'Professional field selection is required',
            'field_exists' => 'The selected professional field is invalid',
            'field_specialty_exists' => 'The selected specialization area is invalid',
            'field_grade_exists' => 'The selected professional grade is invalid',
        ],
        'responses' => [
            'add_success' => 'Professional occupation has been successfully added',
            'update_success' => 'Professional occupation has been successfully updated',
        ],
    ],
    "banking_information" => [
        "agency_fr" => "Bank Branch (French)",
        "agency_ar" => "Bank Branch (Arabic)",
        "agency_en" => "Bank Branch (English)",
        "agency_code" => "Branch Code",
        "account_number" => "Account Number",
        "bank_id" => "Financial Institution",
        'errors' => [
            'bankable_id_required' => 'Associated entity identifier is required',
            'bankable_type_required' => 'Associated entity type is required',
            'bankable_type_invalid' => 'The specified entity type is invalid',
        ],
        'responses' => [
            'add_success' => 'Banking information has been successfully added',
            'update_success' => 'Banking information has been successfully updated',
        ],
    ],
    "bank" => [
        "acronym" => "Acronym",
        "designation_ar" => "Designation in arabic",
        "designation_fr" => "Designation in french",
        "designation_en" => "Designation in english",
        "code" => "Code",
        'responses' => [
            'add_success' => 'You have successfully added a New Bank', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Bank', // Corrected spelling
        ],
    ],
    "guest" => [
        "message" => [
            'name' => "Name",
            'name-placeholder' => "Your name",
            'email' => "Email",
            'email-placeholder' => "Your Email",
            "message" => "Message",
            'message-placeholder' => "Your Message",
            'responses' => [
                'send_success' => 'Your message has been successfully sent. A reply will be sent to your email address',
            ],
        ]
    ],
    "article" => [
        'title_fr' => "Title In French",
        'title_ar' => "Title In Arabic",
        'title_en' => "Title In English",
        "content_fr" => "Content In French",
        "content_en" => "Content In English",
        "content_ar" => "Content In Arabic",
        "published_at" => "Published At",
        "articleable_type" => "Published Type",
        "articleable_id" => "Published In",
        "images" => "Images",
        'responses' => [
            'add_success' => 'You have successfully added a New Article', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Article', // Corrected spelling
        ],
    ],


    "external_link" => [
        "name_fr" => "Name in French",
        "name_ar" => "Name in Arabic",
        "name_en" => "Name in English",
        'url' => "Url",
        "menu_id" => "Menu Name",
        'responses' => [
            'add_success' => 'You have successfully added a New External Link', // Corrected spelling
            'update_success' => 'You have successfully updated the selected External Link', // Corrected spelling
        ],
    ],
    "menu" => [
        'title_fr' => "Title In French",
        'title_ar' => "Title In Arabic",
        'title_en' => "Title In English",
        "type" => "Type",
        "state" => "State",
        'responses' => [
            'add_success' => 'You have successfully added a New Menu', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Menu', // Corrected spelling
        ],
    ],



    "slide" => [
        'title_fr' => "Title In French",
        'title_ar' => "Title In Arabic",
        'title_en' => "Title In English",
        "content_fr" => "Content In French",
        "content_en" => "Content In English",
        "content_ar" => "Content In Arabic",
        "order" => "Slide Order",
        "slider_id" => "Slider",
        'image' => "Image",
        'responses' => [
            'add_success' => 'You have successfully added a New Slide', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Slide', // Corrected spelling
        ],
    ],
    "slider" => [
        "name" => "Slide Title",
        "position" => "Display Position",
        "user_id" => "Published By",
        'state' => "Publication Status",
        'responses' => [
            'add_success' => 'New slide has been successfully added',
            'update_success' => 'Selected slide has been successfully updated',
        ],
    ],
    "trend" => [
        'title_fr' => "Title In French",
        'title_ar' => "Title In Arabic",
        'title_en' => "Title In English",
        "content_fr" => "Content In French",
        "content_en" => "Content In English",
        "content_ar" => "Content In Arabic",
        "start_at" => "From",
        "end_at" => "Until",
        'responses' => [
            'add_success' => 'You have successfully added a New Trend', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Trend', // Corrected spelling
        ],
    ],
    "menu" => [
        'name_fr' => "Name In French",
        'name_ar' => "Name In Arabic",
        'name_en' => "Name In English",
        'url' => "Url",
        'responses' => [
            'add_success' => 'You have successfully added a New Trend', // Corrected spelling
            'update_success' => 'You have successfully updated the selected Trend', // Corrected spelling
        ],
    ],

    'image' => [
        'display_name' => "Display Name",
        'use_case' => "Use Case",
        'real_image' => "Image File",
        'responses' => [
            "add_success" => "Image File added successfully",
            'update_success' => "Image File has been updated successfully",
        ],
    ],

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////               App


    'service' => [
        "name_fr" => "Service Name (Fr)",
        "name_ar" => "Service Name (Ar)",
        "name_en" => "Service Name (En)",
         "introduction_fr"=>"Introduction (Fr)",
         "introduction_ar"=>"Introduction (Ar)",
         "introduction_en"=>"Introduction (En)",
        "specialty" => "Medical Specialty",
        "tel" => "Primary Phone",
        "fax" => "Fax",
        'email'=> "Email",
        'beds_number' =>"Beds Number",
        'specialists_number'=> "Specialist Number",
        'physicians_number'=> "Physicians Number",
        'paramedics_number'=> "Paramedics Number",
        "head_of_service_id" => "Head of Service",
        "establishment_id" => "Affiliated Establishment",

        'responses' => [
            'add_success' => "Healthcare service created successfully",
            'update_success' => "Service updated successfully",
        ],
    ],



    "global_transfer" => [
        'number' => "Transfer Reference",
        'date' => 'Transfer Date',
        'motive_ar' => "Motive (Arabic)",
        'motive_fr' => "Motive (French)",
        'motive_en' => "Motive (English)",
        'user_id' => "Platform Administrator",
        'responses' => [
            'add_success' => 'Global transfer has been successfully created',
            'update_success' => 'Global transfer has been successfully updated',
        ],
    ],

    "transfer" => [
        'amount' => "Amount",
        'user_id' => "Beneficiary",
        'global_bank_transfer_id' => "Global Transfer",
        'responses' => [
            'add_success' => 'Transfer has been successfully created',
            'update_success' => 'Transfer has been successfully updated',
        ],
    ],

    "bonus" => [
        "amount" => "Amount",
        "titled_ar" => "Title (Arabic)",
        "titled_fr" => "Title (French)",
        "titled_en" => "Title (English)",
        'responses' => [
            'add_success' => 'Bonus has been successfully created',
            'update_success' => 'Bonus has been successfully updated',
        ],
    ],

];
