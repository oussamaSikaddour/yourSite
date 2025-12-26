<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldGrade;
use Illuminate\Database\Seeder;

class FieldGradeSeeder extends Seeder {
    public function run(): void {
         $medical = Field::where('acronym', 'F_MED')->first();

        if (!$medical) return;

        $grades = [
            ['en' => 'Intern',                'fr' => 'Interne',               'ar' => 'متدرب',              'acronym' => 'G_INT'],
            ['en' => 'General Practitioner',                'fr' => 'Généraliste',               'ar' => 'ممارس عام',              'acronym' => 'G_GPRAC'],
            ['en' => 'Resident',              'fr' => 'Résident',              'ar' => 'طبيب مقيم',          'acronym' => 'G_RES'],
            ['en' => 'Senior Resident',       'fr' => 'Résident senior',       'ar' => 'طبيب مقيم أول',      'acronym' => 'G_SRR'],
            ['en' => 'Specialist',            'fr' => 'Spécialiste',           'ar' => 'اختصاصي',            'acronym' => 'G_SPE'],
            ['en' => 'Senior Specialist',     'fr' => 'Spécialiste senior',    'ar' => 'اختصاصي أول',        'acronym' => 'G_SSP'],
            ['en' => 'Consultant',            'fr' => 'Consultant',            'ar' => 'استشاري',            'acronym' => 'G_CON'],
            ['en' => 'Senior Consultant',     'fr' => 'Consultant senior',     'ar' => 'استشاري أول',       'acronym' => 'G_SCN'],
            ['en' => 'Head of Department',    'fr' => 'Chef de service',       'ar' => 'رئيس القسم',         'acronym' => 'G_HOD'],
            ['en' => 'Chief Medical Officer', 'fr' => 'Directeur médical',     'ar' => 'المدير الطبي',       'acronym' => 'G_CMO'],
        ];

        foreach ($grades as $data) {
            FieldGrade::create([
                'field_id' => $medical->id,
                'designation_en' => $data['en'],
                'designation_fr' => $data['fr'],
                'designation_ar' => $data['ar'],
                'acronym' => $data['acronym'],
            ]);
        }
    }
}
