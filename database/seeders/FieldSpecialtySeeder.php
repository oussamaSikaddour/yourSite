<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldSpecialty;
use Illuminate\Database\Seeder;

class FieldSpecialtySeeder extends Seeder {
    public function run(): void {
        $medical = Field::where('acronym', 'F_MED')->first();

        if (!$medical) return;

        $specialties = [
            ['en' => 'Allergology', 'fr' => 'Allergologie', 'ar' => 'الحساسية والمناعة', 'acronym' => 'S_ALL'],
            ['en' => 'Anesthesiology', 'fr' => 'Anesthésiologie', 'ar' => 'علم التخدير', 'acronym' => 'S_ANE'],
            ['en' => 'Andrology', 'fr' => 'Andrologie', 'ar' => 'علم الذكورة', 'acronym' => 'S_AND'],
            ['en' => 'Cardiology', 'fr' => 'Cardiologie', 'ar' => 'طب القلب', 'acronym' => 'S_CAR'],
            ['en' => 'Cardiac Surgery', 'fr' => 'Chirurgie cardiaque', 'ar' => 'جراحة القلب', 'acronym' => 'S_CSU'],
            ['en' => 'Aesthetic, Plastic, and Reconstructive Surgery', 'fr' => 'Chirurgie plastique esthétique et reconstructrice', 'ar' => 'جراحة التجميل والترميمية', 'acronym' => 'S_APR'],
            ['en' => 'General Surgery', 'fr' => 'Chirurgie générale', 'ar' => 'جراحة عامة', 'acronym' => 'S_GSU'],
            ['en' => 'Maxillofacial Surgery', 'fr' => 'Chirurgie maxillo-faciale', 'ar' => 'جراحة الفك والوجه', 'acronym' => 'S_MAX'],
            ['en' => 'Pediatric Surgery', 'fr' => 'Chirurgie pédiatrique', 'ar' => 'جراحة الأطفال', 'acronym' => 'S_PED'],
            ['en' => 'Thoracic Surgery', 'fr' => 'Chirurgie thoracique', 'ar' => 'جراحة الصدر', 'acronym' => 'S_THO'],
            ['en' => 'Vascular Surgery', 'fr' => 'Chirurgie vasculaire', 'ar' => 'جراحة الأوعية الدموية', 'acronym' => 'S_VAS'],
            ['en' => 'Neurosurgery', 'fr' => 'Neurochirurgie', 'ar' => 'جراحة الأعصاب', 'acronym' => 'S_NSU'],
            ['en' => 'Dermatology', 'fr' => 'Dermatologie', 'ar' => 'طب الأمراض الجلدية', 'acronym' => 'S_DER'],
            ['en' => 'Endocrinology', 'fr' => 'Endocrinologie', 'ar' => 'علم الغدد الصماء', 'acronym' => 'S_END'],
            ['en' => 'Gastroenterology', 'fr' => 'Gastro-entérologie', 'ar' => 'الجهاز الهضمي', 'acronym' => 'S_GAS'],
            ['en' => 'Geriatrics', 'fr' => 'Gériatrie', 'ar' => 'طب المسنين', 'acronym' => 'S_GER'],
            ['en' => 'Obstetrics and Gynecology', 'fr' => 'Obstétrique et gynécologie', 'ar' => 'طب النساء والتوليد', 'acronym' => 'S_OBG'],
            ['en' => 'Hematology', 'fr' => 'Hématologie', 'ar' => 'علم الدم', 'acronym' => 'S_HEM'],
            ['en' => 'Hepatology', 'fr' => 'Hépatologie', 'ar' => 'علم الكبد', 'acronym' => 'S_HEP'],
            ['en' => 'Infectious Diseases', 'fr' => 'Maladies infectieuses', 'ar' => 'الأمراض المعدية', 'acronym' => 'S_INF'],
            ['en' => 'Occupational Medicine', 'fr' => 'Médecine du travail', 'ar' => 'الطب الوظيفي', 'acronym' => 'S_OCC'],
            ['en' => 'Nuclear Medicine', 'fr' => 'Médecine nucléaire', 'ar' => 'الطب النووي', 'acronym' => 'S_NUC'],
            ['en' => 'Neonatology', 'fr' => 'Néonatologie', 'ar' => 'طب حديثي الولادة', 'acronym' => 'S_NEO'],
            ['en' => 'Nephrology', 'fr' => 'Néphrologie', 'ar' => 'علم الكلى', 'acronym' => 'S_NEP'],
            ['en' => 'Neurology', 'fr' => 'Neurologie', 'ar' => 'طب الأعصاب', 'acronym' => 'S_NEU'],
            ['en' => 'Dentistry', 'fr' => 'Dentisterie', 'ar' => 'طب الأسنان', 'acronym' => 'S_DEN'],
            ['en' => 'Oncology', 'fr' => 'Oncologie', 'ar' => 'علم الأورام', 'acronym' => 'S_ONC'],
            ['en' => 'Ophthalmology', 'fr' => 'Ophtalmologie', 'ar' => 'طب العيون', 'acronym' => 'S_OPH'],
            ['en' => 'Orthopedics', 'fr' => 'Orthopédie', 'ar' => 'طب العظام', 'acronym' => 'S_ORT'],
            ['en' => 'Pediatrics', 'fr' => 'Pédiatrie', 'ar' => 'طب الأطفال', 'acronym' => 'S_PDT'],
            ['en' => 'Pulmonology', 'fr' => 'Pneumologie', 'ar' => 'أمراض الرئة', 'acronym' => 'S_PUL'],
            ['en' => 'Radiology', 'fr' => 'Radiologie', 'ar' => 'الأشعة التشخيصية', 'acronym' => 'S_RAD'],
            ['en' => 'Radiotherapy', 'fr' => 'Radiothérapie', 'ar' => 'العلاج الإشعاعي', 'acronym' => 'S_RTH'],
            ['en' => 'Rheumatology', 'fr' => 'Rhumatologie', 'ar' => 'أمراض المفاصل', 'acronym' => 'S_RHE'],
            ['en' => 'Urology', 'fr' => 'Urologie', 'ar' => 'المسالك البولية', 'acronym' => 'S_URO'],
        ];

        foreach ($specialties as $data) {
            FieldSpecialty::create([
                'field_id' => $medical->id,
                'designation_en' => $data['en'],
                'designation_fr' => $data['fr'],
                'designation_ar' => $data['ar'],
                'acronym' => $data['acronym'],
            ]);
        }
    }
}
