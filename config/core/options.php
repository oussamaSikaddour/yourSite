<?php

return [

    "STATUS_OPTIONS" => [
        'en' => ['0' => 'Inactive', '1' => 'Active'],
        'fr' => ['0' => 'Inactif', '1' => 'Actif'],
        'ar' => ['0' => 'غير نشط', '1' => 'نشط'],
    ],

    "PUBLISHING_STATE" => [
        "fr" => ["" => "--- spécifier l'état ---", 'published' => 'Publié', 'not_published' => 'Non publié'],
        "en" => ["" => "--- Specify State ---", 'published' => 'Published', 'not_published' => 'Not Published'],
        "ar" => ["" => "--- حدد الحالة ---", 'published' => 'تم النشر', 'not_published' => 'غير منشور'],
    ],

    "MARITAL_STATE" => [
        "en" => ['' => '--- Specify ---', 'single' => 'Single', 'married' => 'Married', 'widowed' => 'Widowed', 'divorced' => 'Divorced'],
        "fr" => ['' => '--- spécifier ---', 'single' => 'Célibataire', 'married' => 'Marié(e)', 'widowed' => 'Veuf(ve)', 'divorced' => 'Divorcé(e)'],
        "ar" => ['' => '--- حدد ---', 'single' => 'أعزب', 'married' => 'متزوج', 'widowed' => 'أرمل', 'divorced' => 'مطلق'],
    ],

    "GENDER" => [
        "en" => ['' => '--- Specify ---', 'male' => 'Male', 'female' => 'Female', 'other' => "Other"],
        "fr" => ['' => '--- spécifier ---', 'male' => 'Homme', 'female' => 'Femme', "other" => 'Autre'],
        "ar" => ['' => '--- حدد ---', 'male' => 'ذكر', 'female' => 'أنثى', "other" => "آخر"],
    ],

    "MENU_TYPES" => [
        "en" => ['', 'external_links' => 'External Links', 'internal_links' => 'Internal Links'],
        "fr" => ['', 'external_links' => 'Liens externes', 'internal_links' => 'Liens internes'],
        "ar" => ['', 'external_links' => 'الروابط الخارجية', 'internal_links' => 'الروابط الداخلية'],
    ],

    "SERVICE_TYPE" => [
        "fr" => ['', 'administration' => 'Administration', 'health' => 'Santé'],
        'en' => ['', 'administration' => 'Administration', 'health' => 'Health'],
        "ar" => ['', 'administration' => 'إدارة', 'health' => 'صحة'],
    ],

    "ESTABLISHMENT_TYPES" => [
        "en" => ["appointment_locations" => "Appointments Location", "clinic" => "Clinic"],
        "fr" => ["appointment_locations" => "Lieu de rendez-vous", "clinic" => "Clinique"],
        "ar" => ["appointment_locations" => "مكان المواعيد", "clinic" => "عيادة"],
    ],

    "APPOINTMENT_TYPES" => [
        "en" => ["initial" => "Initial Consultation", "follow-up" => "Follow-up Appointment"],
        "fr" => ["initial" => "Consultation Initiale", "follow-up" => "Rendez-vous de Suivi"],
        "ar" => ["initial" => "الاستشارة الأولية", "follow-up" => "موعد المتابعة"],
    ],

];
