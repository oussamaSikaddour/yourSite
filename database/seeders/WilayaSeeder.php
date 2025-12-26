<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;

class WilayaSeeder extends Seeder
{
    public function run(): void
    {
        $wilayas = [
            ['code' => '01', 'designation_en' => 'Adrar',            'designation_fr' => 'Adrar',            'designation_ar' => 'أدرار'],
            ['code' => '02', 'designation_en' => 'Chlef',            'designation_fr' => 'Chlef',            'designation_ar' => 'الشلف'],
            ['code' => '03', 'designation_en' => 'Laghouat',         'designation_fr' => 'Laghouat',         'designation_ar' => 'الأغواط'],
            ['code' => '04', 'designation_en' => 'Oum El Bouaghi',   'designation_fr' => 'Oum El Bouaghi',   'designation_ar' => 'أم البواقي'],
            ['code' => '05', 'designation_en' => 'Batna',            'designation_fr' => 'Batna',            'designation_ar' => 'باتنة'],
            ['code' => '06', 'designation_en' => 'Béjaïa',           'designation_fr' => 'Béjaïa',           'designation_ar' => 'بجاية'],
            ['code' => '07', 'designation_en' => 'Biskra',           'designation_fr' => 'Biskra',           'designation_ar' => 'بسكرة'],
            ['code' => '08', 'designation_en' => 'Béchar',           'designation_fr' => 'Béchar',           'designation_ar' => 'بشار'],
            ['code' => '09', 'designation_en' => 'Blida',            'designation_fr' => 'Blida',            'designation_ar' => 'البليدة'],
            ['code' => '10', 'designation_en' => 'Bouira',           'designation_fr' => 'Bouira',           'designation_ar' => 'البويرة'],
            ['code' => '11', 'designation_en' => 'Tamanrasset',      'designation_fr' => 'Tamanrasset',      'designation_ar' => 'تمنراست'],
            ['code' => '12', 'designation_en' => 'Tébessa',          'designation_fr' => 'Tébessa',          'designation_ar' => 'تبسة'],
            ['code' => '13', 'designation_en' => 'Tlemcen',          'designation_fr' => 'Tlemcen',          'designation_ar' => 'تلمسان'],
            ['code' => '14', 'designation_en' => 'Tiaret',           'designation_fr' => 'Tiaret',           'designation_ar' => 'تيارت'],
            ['code' => '15', 'designation_en' => 'Tizi Ouzou',       'designation_fr' => 'Tizi Ouzou',       'designation_ar' => 'تيزي وزو'],
            ['code' => '16', 'designation_en' => 'Algiers',          'designation_fr' => 'Alger',            'designation_ar' => 'الجزائر'],
            ['code' => '17', 'designation_en' => 'Djelfa',           'designation_fr' => 'Djelfa',           'designation_ar' => 'الجلفة'],
            ['code' => '18', 'designation_en' => 'Jijel',            'designation_fr' => 'Jijel',            'designation_ar' => 'جيجل'],
            ['code' => '19', 'designation_en' => 'Sétif',            'designation_fr' => 'Sétif',            'designation_ar' => 'سطيف'],
            ['code' => '20', 'designation_en' => 'Saïda',            'designation_fr' => 'Saïda',            'designation_ar' => 'سعيدة'],
            ['code' => '21', 'designation_en' => 'Skikda',           'designation_fr' => 'Skikda',           'designation_ar' => 'سكيكدة'],
            ['code' => '22', 'designation_en' => 'Sidi Bel Abbès',   'designation_fr' => 'Sidi Bel Abbès',   'designation_ar' => 'سيدي بلعباس'],
            ['code' => '23', 'designation_en' => 'Annaba',           'designation_fr' => 'Annaba',           'designation_ar' => 'عنابة'],
            ['code' => '24', 'designation_en' => 'Guelma',           'designation_fr' => 'Guelma',           'designation_ar' => 'قالمة'],
            ['code' => '25', 'designation_en' => 'Constantine',      'designation_fr' => 'Constantine',      'designation_ar' => 'قسنطينة'],
            ['code' => '26', 'designation_en' => 'Médéa',            'designation_fr' => 'Médéa',            'designation_ar' => 'المدية'],
            ['code' => '27', 'designation_en' => 'Mostaganem',       'designation_fr' => 'Mostaganem',       'designation_ar' => 'مستغانم'],
            ['code' => '28', 'designation_en' => 'M\'Sila',          'designation_fr' => 'M\'Sila',          'designation_ar' => 'المسيلة'],
            ['code' => '29', 'designation_en' => 'Mascara',          'designation_fr' => 'Mascara',          'designation_ar' => 'معسكر'],
            ['code' => '30', 'designation_en' => 'Ouargla',          'designation_fr' => 'Ouargla',          'designation_ar' => 'ورقلة'],
            ['code' => '31', 'designation_en' => 'Oran',             'designation_fr' => 'Oran',             'designation_ar' => 'وهران'],
            ['code' => '32', 'designation_en' => 'El Bayadh',        'designation_fr' => 'El Bayadh',        'designation_ar' => 'البيض'],
            ['code' => '33', 'designation_en' => 'Illizi',           'designation_fr' => 'Illizi',           'designation_ar' => 'إليزي'],
            ['code' => '34', 'designation_en' => 'Bordj Bou Arreridj','designation_fr' => 'Bordj Bou Arreridj','designation_ar' => 'برج بوعريريج'],
            ['code' => '35', 'designation_en' => 'Boumerdès',        'designation_fr' => 'Boumerdès',        'designation_ar' => 'بومرداس'],
            ['code' => '36', 'designation_en' => 'El Tarf',          'designation_fr' => 'El Tarf',          'designation_ar' => 'الطارف'],
            ['code' => '37', 'designation_en' => 'Tindouf',          'designation_fr' => 'Tindouf',          'designation_ar' => 'تندوف'],
            ['code' => '38', 'designation_en' => 'Tissemsilt',       'designation_fr' => 'Tissemsilt',       'designation_ar' => 'تيسمسيلت'],
            ['code' => '39', 'designation_en' => 'El Oued',          'designation_fr' => 'El Oued',          'designation_ar' => 'الوادي'],
            ['code' => '40', 'designation_en' => 'Khenchela',        'designation_fr' => 'Khenchela',        'designation_ar' => 'خنشلة'],
            ['code' => '41', 'designation_en' => 'Souk Ahras',       'designation_fr' => 'Souk Ahras',       'designation_ar' => 'سوق أهراس'],
            ['code' => '42', 'designation_en' => 'Tipaza',           'designation_fr' => 'Tipaza',           'designation_ar' => 'تيبازة'],
            ['code' => '43', 'designation_en' => 'Mila',             'designation_fr' => 'Mila',             'designation_ar' => 'ميلة'],
            ['code' => '44', 'designation_en' => 'Aïn Defla',        'designation_fr' => 'Aïn Defla',        'designation_ar' => 'عين الدفلى'],
            ['code' => '45', 'designation_en' => 'Naâma',            'designation_fr' => 'Naâma',            'designation_ar' => 'النعامة'],
            ['code' => '46', 'designation_en' => 'Aïn Témouchent',   'designation_fr' => 'Aïn Témouchent',   'designation_ar' => 'عين تموشنت'],
            ['code' => '47', 'designation_en' => 'Ghardaïa',         'designation_fr' => 'Ghardaïa',         'designation_ar' => 'غرداية'],
            ['code' => '48', 'designation_en' => 'Relizane',         'designation_fr' => 'Relizane',         'designation_ar' => 'غليزان'],
            ['code' => '49', 'designation_en' => 'Timimoun',         'designation_fr' => 'Timimoun',         'designation_ar' => 'تيميمون'],
            ['code' => '50', 'designation_en' => 'Bordj Badji Mokhtar','designation_fr' => 'Bordj Badji Mokhtar','designation_ar' => 'برج باجي مختار'],
            ['code' => '51', 'designation_en' => 'Ouled Djellal',    'designation_fr' => 'Ouled Djellal',    'designation_ar' => 'أولاد جلال'],
            ['code' => '52', 'designation_en' => 'Béni Abbès',       'designation_fr' => 'Béni Abbès',       'designation_ar' => 'بني عباس'],
            ['code' => '53', 'designation_en' => 'In Salah',         'designation_fr' => 'In Salah',         'designation_ar' => 'عين صالح'],
            ['code' => '54', 'designation_en' => 'In Guezzam',       'designation_fr' => 'In Guezzam',       'designation_ar' => 'عين قزام'],
            ['code' => '55', 'designation_en' => 'Touggourt',        'designation_fr' => 'Touggourt',        'designation_ar' => 'تقرت'],
            ['code' => '56', 'designation_en' => 'Djanet',           'designation_fr' => 'Djanet',           'designation_ar' => 'جانت'],
            ['code' => '57', 'designation_en' => 'El M\'ghair',      'designation_fr' => 'El M\'ghair',      'designation_ar' => 'المغير'],
            ['code' => '58', 'designation_en' => 'El Meniaa',        'designation_fr' => 'El Meniaa',        'designation_ar' => 'المنيعة'],
        ];

        foreach ($wilayas as $data) {
            Wilaya::create($data);
        }
    }
}
