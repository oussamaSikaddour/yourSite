<?php

return [

    // Static years replaced with dynamic generation
    "YEARS" => array_combine(
        range(now()->year, 2050),
        range(now()->year, 2050)
    ),
    "INAUGURAL_YEARS" => array_combine(
        range(1962,now()->year),
        range(1962,now()->year)
    ),

    "MONTHS_OPTIONS" => [
        "fr" => [
            "" => "--- Choisir Un Mois ---",
            '1' => 'Janvier', '2' => 'Février', '3' => 'Mars', '4' => 'Avril',
            '5' => 'Mai', '6' => 'Juin', '7' => 'Juillet', '8' => 'Août',
            '9' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre',
        ],
        "en" => [
            "" => "--- Choose a Month ---",
            '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April',
            '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August',
            '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
        ],
        "ar" => [
            "" => "--- اختر شهرًا ---",
            '1' => 'يناير', '2' => 'فبراير', '3' => 'مارس', '4' => 'أبريل',
            '5' => 'مايو', '6' => 'يونيو', '7' => 'يوليو', '8' => 'أغسطس',
            '9' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر',
        ],
    ],

    "MONTHS" => [
        "fr" => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        "ar" => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
        "en" => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    ],

    "HOURS" => array_reduce(range(0, 23), function ($acc, $hour) {
        $formatted = str_pad($hour, 2, '0', STR_PAD_LEFT) . ":00";
        $acc[$formatted . ":00"] = $formatted;
        return $acc;
    }, []),

    "WEEK_DAYS" => [
        "fr" => [
            "0" => "Dimanche", "1" => "Lundi", "2" => "Mardi", "3" => "Mercredi",
            "4" => "Jeudi", "5" => "Vendredi", "6" => "Samedi",
        ],
        "en" => [
            "0" => "Sunday", "1" => "Monday", "2" => "Tuesday", "3" => "Wednesday",
            "4" => "Thursday", "5" => "Friday", "6" => "Saturday",
        ],
        "ar" => [
            "0" => "الأحد", "1" => "الاثنين", "2" => "الثلاثاء", "3" => "الأربعاء",
            "4" => "الخميس", "5" => "الجمعة", "6" => "السبت",
        ],
    ],

];
