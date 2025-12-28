<?php

return [

    'service_details' => [

        'head_of_service' => 'Head of Service',
        'overview'        => 'Service Overview',

        'beds'            => 'Beds',
        'specialists'     => 'Specialists',
        'physicians'      => 'Physicians',
        'paramedics'      => 'Paramedics',

    ],

     "article"=>[
        'extend'=>"Read More",
        'less'  => "Read LeSS"
     ],
     'error'=>[
         '403'=>[
            'title'=>"Access Denied",
            'text'=>"You don’t have permission to access this page."
         ],
         '404'=>[
            'title'=>"Page Not Found",
            "text"=>"The page you’re looking for doesn’t exist or has been moved."
         ],
         '419'=>[
            'title'=>'Session Expired',
            'text'=>'Please refresh the page or log in again.'
         ],
         '429'=>[
            'title'=>'Too Many Requests',
            'text'=>'You’re making requests too quickly. Please slow down.'
         ],
         '500'=>[
            'title'=>'Server Error',
            'text'=>'Something went wrong on our end. Please try again later.'
         ],
         "503"=>[
            "title"=>'Under Maintenance',
            'text'=>'We’re performing some updates. Please check back soon.'
         ]


     ]

];
