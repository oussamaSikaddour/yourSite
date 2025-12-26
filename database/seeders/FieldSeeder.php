<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder {
    public function run(): void {
        $fields = [
            ['designation_en' => 'Medical',                    'designation_fr' => 'Médical',                      'designation_ar' => 'الطب',                        'acronym' => 'F_MED'],
            ['designation_en' => 'Pharmacy',                   'designation_fr' => 'Pharmacie',                    'designation_ar' => 'الصيدلة',                     'acronym' => 'F_PHA'],
            ['designation_en' => 'Military / Army',            'designation_fr' => 'Militaire / Armée',            'designation_ar' => 'العسكرية / الجيش',            'acronym' => 'F_MIL'],
            ['designation_en' => 'Education & Teaching',       'designation_fr' => 'Éducation & Enseignement',     'designation_ar' => 'التعليم والتدريس',            'acronym' => 'F_EDU'],
            ['designation_en' => 'Information Technology',     'designation_fr' => 'Technologies de l\'information','designation_ar' => 'تكنولوجيا المعلومات',         'acronym' => 'F_IT'],
            ['designation_en' => 'Engineering',                'designation_fr' => 'Ingénierie',                   'designation_ar' => 'الهندسة',                      'acronym' => 'F_ENG'],
            ['designation_en' => 'Law',                        'designation_fr' => 'Droit',                         'designation_ar' => 'القانون',                      'acronym' => 'F_LAW'],
            ['designation_en' => 'Finance & Accounting',       'designation_fr' => 'Finance & Comptabilité',       'designation_ar' => 'المالية والمحاسبة',            'acronym' => 'F_FIN'],
            ['designation_en' => 'Administration & Management','designation_fr' => 'Administration & Gestion',     'designation_ar' => 'الإدارة',                      'acronym' => 'F_ADM'],
            ['designation_en' => 'Agriculture',                'designation_fr' => 'Agriculture',                  'designation_ar' => 'الزراعة',                      'acronym' => 'F_AGR'],
            ['designation_en' => 'Art & Design',               'designation_fr' => 'Art & Design',                  'designation_ar' => 'الفن والتصميم',                'acronym' => 'F_ART'],
            ['designation_en' => 'Media & Communication',     'designation_fr' => 'Médias & Communication',        'designation_ar' => 'الإعلام والاتصال',             'acronym' => 'F_MEDI'], // renamed acronym
            ['designation_en' => 'Construction & Architecture','designation_fr' => 'Construction & Architecture',  'designation_ar' => 'البناء والهندسة المعمارية',     'acronym' => 'F_CON'],
        ];

        foreach ($fields as $data) {
            Field::create($data);
        }
    }
}
