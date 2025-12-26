<?php

return [
    'site_parameters' => [
        'name' => 'Site Settings',
        'titles' => [
            'main' => 'Site Settings',
        ],
    ],
    "login" => [
        "name" => "Login",

        'links' => [
            'register' => 'New to ' . config('app.name') . ' ? Register Now',
            'forgot_password' => 'Forgot Your Password?',
        ],

        "titles" => [
            'main' => 'Sign In',
        ]
    ],
    'register' => [
        'name' => 'Register',
        'links' => [
            'login' => 'Already have an account?',
        ],
        'titles' => [
            'main' => 'Sign Up',
        ]
    ],
    "logout" => "Log Out",
    'forgot_password' => [
        'name' => 'Forgot Password',
        'titles' => [
            "main" => 'Recover Your Account',
        ]
    ],
    "profile" => [
        'name' => "Profile",
        "titles" => [
            "main" => "Welcome to Your Profile"
        ]
    ],

    "change_password" => [
        'name' => "Change Password",
        "titles" => [
            "main" => "Change Your Password"
        ]
    ],
    "change_email" => [
        "name" => "Change Email",
        "titles" => [
            "main" => "Change Your Email"
        ]
    ],

    "dashboard" => [
        'name' => "Dashboard",
        "titles" => [
            "main" => "Welcome to the Dashboard :name"
        ]
    ],
    "super_admin_space" => [
        'name' => "Super Admin Dashboard",
        "titles" => [
            "main" => "Welcome to the Super Admin Dashboard"
        ]
    ],

    "manage_users" => [
        'name' => "Manage Users",
        "titles" => [
            "main" => "Manage Users"
        ]
    ],
    "manage_persons" => [
        'name' => "Manage Persons",
        "titles" => [
            "main" => "Manage Persons"
        ]
    ],

    "manage_social_works" => [
        'name' => "Social Works",
        "titles" => [
            "main" => "Manage Social Works"
        ]
    ],



    "wilaya" => [
        'name' => "States",
        "titles" => [
            "main" => "State Management"
        ]
    ],
    "dairates" => [
        'name' => "Districts",
        "titles" => [
            "main" => "District Management (State Code: :code)"
        ]
    ],
    "occupation_fields" => [
        'name' => "Professional Fields",
        "titles" => [
            "main" => "Manage Professional Fields"
        ]
    ],


    "general_infos" => [
        "name" => "Manage General Information",
        "titles" => [
            "main" => "Manage App General Information"
        ],
    ],
    "manage_hero" => [
        "name" => "Manage Hero Section",
        "titles" => [
            "main" => "Manage Hero Section"
        ],
    ],
    "manage_about_us" => [
        "name" => "Manage About Us Section",
        "titles" => [
            "main" => "Manage About Us Section"
        ],
    ],
    "manage_our_qualities" => [
        "name" => "Manage Our Qualities Section",
        "titles" => [
            "main" => "Manage Our Qualities Section"
        ],
    ],

    'messages' => [
        'name' => "Visitors' Messages",
        'titles' => [
            'main' => "Visitors' Messages Inbox",
        ],
    ],
    'banks' => [
        'name' => 'Manage Banks',
        'titles' => [
            'main' => 'Manage Banks',
        ],
    ],

    'articles' => [
        'name' => 'Manage Articles',
        'titles' => [
            'main' => 'Manage Articles',
        ],
    ],

    'menus' => [
        'name' => 'Manage Menus',
        'titles' => [
            'main' => 'Manage Menus',
        ],
    ],

    'menu' => [
        'name' => 'Manage Menu',
        'titles' => [
            'main' => 'Manage External Links of Menu :title',
        ],
    ],
    'sliders' => [
        'name' => 'Manage Sliders',
        'titles' => [
            'main' => 'Manage Sliders',
        ],
    ],
    'slider' => [
        'name' => 'Manage Slides',
        'titles' => [
            'main' => 'Manage :name Slides',
        ],
    ],
    'trends' => [
        'name' => 'Manage Trends',
        'titles' => [
            'main' => 'Manage Trends',
        ],
    ],

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           App

    "landing_page" => [
        "name" => "Landing Page",
        "links" => [
            'hero' => "Hero",
            'about_us' => "About Us",
             "services"=> "Services",
            'contact_us' => "Contact Us"
        ],
        "sections" => [
            "hero" => [
                "call_to_actions" => [
                    'contact_us' => "Contact Us",
                    'get_started' => "Begin Journey"
                ]
            ],
            "about_us" => [
              "title"=>"About Us",

              "beds"=>"Beds",
              "services"=>"Services",
              "years"=>"Years"
            ],
             "services"=>[
                'title'=>"Our Services",
                 'sub_title'=>"Committed to Quality, Care, and Reliability"
             ],
            'contact_us' => [
                'title' => "Get In Touch",
                'sub_title'=>"Please provide a valid email address so we can respond to your inquiry.",
                'coordinates' => "Our Coordinates",
                'location' => "Address",
                'email' => "Email",
                'phone' => "Phone",
                'fax' => "Fax",
            ],
            "footer" => [
                'copyright' => "Copyright",
                "agency" => ":name",
                "reservation" => "All Rights Reserved",
                 'designed_by'=>"Designed By",
                 "links"=>[
                    "privacy_policy"=>"Privacy Policy",
                    'terms_of_service'=>"Terms Of Service",
                    'cookie_policy'=>"Cookie Policy"
                 ]
            ]
        ]
    ],

    "services_public"=>[
        "name"=>"Our Services",
        'title'=>"Our Services",
    ],
    "service_details_public"=>[
        "name"=>"Our Service",
        'title'=>"The Service :name details",
    ],
    'services' => [
        'name' => 'Manage Departments',
        'titles' => [
            'main' => 'Manage Departments',
        ],
    ],
    'service' => [
        'default' => [
            "name" => "Manage Department",
            "titles" => [
                "main" => "Manage Department"
            ]
        ],
        'name' => ':name',
        'titles' => [
            'main' => 'Manage Department :name',
        ],
    ],
    'bonuses' => [
        'name' => 'Manage Bonuses',
        'titles' => [
            'main' => 'Manage Bonuses',
        ],
    ],
    'global_transfers' => [
        'name' => 'Manage Global Transfers',
        'titles' => [
            'main' => 'Manage Global Transfers',
        ],
    ],
    'global_transfer_details' => [
        'name' => 'Global Transfer Details',
        'titles' => [
            'main' => 'Manage :motive Global Transfer Details',
        ],
    ],
];
