<?php

namespace Database\Seeders;

use App\Models\Commune;
use App\Models\Daira;
use App\Models\Wilaya;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DairaSeeder extends Seeder
{
    public function run(): void {
         $wilaya = Wilaya::where('code', '13')->first();

        if (!$wilaya) return;

        $dairates =[
    [
        'code' => '1301',
        'designation_ar' => 'تلمسان',
        'designation_fr' => 'Tlemcen',
        'designation_en' => 'Tlemcen',
        'communes' => [
            ['code' => '130101', 'designation_ar' => 'تلمسان', 'designation_fr' => 'Tlemcen', 'designation_en' => 'Tlemcen'],
        ],
    ],
    [
        'code' => '1302',
        'designation_ar' => 'منصورة',
        'designation_fr' => 'Mansourah',
        'designation_en' => 'Mansourah',
        'communes' => [
            ['code' => '130201', 'designation_ar' => 'منصورة', 'designation_fr' => 'Mansourah', 'designation_en' => 'Mansourah'],
            ['code' => '130202', 'designation_ar' => 'بني مستر', 'designation_fr' => 'Beni Mester', 'designation_en' => 'Beni Mester'],
            ['code' => '130203', 'designation_ar' => 'عين غرابة', 'designation_fr' => 'Aïn Ghoraba', 'designation_en' => 'Ain Ghoraba'],
            ['code' => '130204', 'designation_ar' => 'ترني بني هديل', 'designation_fr' => 'Terny Beni Hdiel', 'designation_en' => 'Terny Beni Hdiel'],
        ],
    ],
    [
        'code' => '1303',
        'designation_ar' => 'مغنية',
        'designation_fr' => 'Maghnia',
        'designation_en' => 'Maghnia',
        'communes' => [
            ['code' => '130301', 'designation_ar' => 'مغنية', 'designation_fr' => 'Maghnia', 'designation_en' => 'Maghnia'],
            ['code' => '130302', 'designation_ar' => 'حمام بوغرارة', 'designation_fr' => 'Hammam Boughrara', 'designation_en' => 'Hammam Boughrara'],
            ['code' => '130303', 'designation_ar' => 'سيدي مجاهد', 'designation_fr' => 'Sidi Medjahed', 'designation_en' => 'Sidi Medjahed'],
        ],
    ],
    [
        'code' => '1304',
        'designation_ar' => 'رمشي',
        'designation_fr' => 'Remchi',
        'designation_en' => 'Remchi',
        'communes' => [
            ['code' => '130401', 'designation_ar' => 'رمشي', 'designation_fr' => 'Remchi', 'designation_en' => 'Remchi'],
            ['code' => '130402', 'designation_ar' => 'عين يوسف', 'designation_fr' => 'Aïn Youcef', 'designation_en' => 'Ain Youcef'],
            ['code' => '130403', 'designation_ar' => 'بني وارسوس', 'designation_fr' => 'Beni Ouarsous', 'designation_en' => 'Beni Ouarsous'],
            ['code' => '130404', 'designation_ar' => 'سبع شيوخ', 'designation_fr' => 'Sebaa Chioukh', 'designation_en' => 'Sebaa Chioukh'],
            ['code' => '130405', 'designation_ar' => 'الفحول', 'designation_fr' => 'El Fehoul', 'designation_en' => 'El Fehoul'],
        ],
    ],
    [
        'code' => '1305',
        'designation_ar' => 'سبدو',
        'designation_fr' => 'Sebdou',
        'designation_en' => 'Sebdou',
        'communes' => [
            ['code' => '130501', 'designation_ar' => 'سبدو', 'designation_fr' => 'Sebdou', 'designation_en' => 'Sebdou'],
            ['code' => '130502', 'designation_ar' => 'العريشة', 'designation_fr' => 'El Aricha', 'designation_en' => 'El Aricha'],
            ['code' => '130503', 'designation_ar' => 'تيرني', 'designation_fr' => 'Terni', 'designation_en' => 'Terni'],
        ],
    ],
    [
        'code' => '1306',
        'designation_ar' => 'ندروما',
        'designation_fr' => 'Nedroma',
        'designation_en' => 'Nedroma',
        'communes' => [
            ['code' => '130601', 'designation_ar' => 'ندروما', 'designation_fr' => 'Nedroma', 'designation_en' => 'Nedroma'],
            ['code' => '130602', 'designation_ar' => 'بني مهدي', 'designation_fr' => 'Beni Medel', 'designation_en' => 'Beni Medel'],
            ['code' => '130603', 'designation_ar' => 'بني سنوس', 'designation_fr' => 'Beni Snous', 'designation_en' => 'Beni Snous'],
            ['code' => '130604', 'designation_ar' => 'عين تالوت', 'designation_fr' => 'Ain Tallout', 'designation_en' => 'Ain Tallout'],
        ],
    ],
    [
        'code' => '1307',
        'designation_ar' => 'الغزوات',
        'designation_fr' => 'Ghazaouet',
        'designation_en' => 'Ghazaouet',
        'communes' => [
            ['code' => '130701', 'designation_ar' => 'الغزوات', 'designation_fr' => 'Ghazaouet', 'designation_en' => 'Ghazaouet'],
            ['code' => '130702', 'designation_ar' => 'دار يغمراسن', 'designation_fr' => 'Djebala', 'designation_en' => 'Djebala'],
            ['code' => '130703', 'designation_ar' => 'مرسى بن مهيدي', 'designation_fr' => 'Marsa Ben M\'hidi', 'designation_en' => 'Marsa Ben M\'hidi'],
            ['code' => '130704', 'designation_ar' => 'السواحلية', 'designation_fr' => 'Souahlia', 'designation_en' => 'Souahlia'],
        ],
    ],
    [
        'code' => '1308',
        'designation_ar' => 'هنين',
        'designation_fr' => 'Honaine',
        'designation_en' => 'Honaine',
        'communes' => [
            ['code' => '130801', 'designation_ar' => 'هنين', 'designation_fr' => 'Honaine', 'designation_en' => 'Honaine'],
            ['code' => '130802', 'designation_ar' => 'أولاد ميمون', 'designation_fr' => 'Ouled Mimoun', 'designation_en' => 'Ouled Mimoun'],
        ],
    ],
    [
        'code' => '1309',
        'designation_ar' => 'حناية',
        'designation_fr' => 'Hennaya',
        'designation_en' => 'Hennaya',
        'communes' => [
            ['code' => '130901', 'designation_ar' => 'حناية', 'designation_fr' => 'Hennaya', 'designation_en' => 'Hennaya'],
            ['code' => '130902', 'designation_ar' => 'زريزرة', 'designation_fr' => 'Azails', 'designation_en' => 'Azails'],
            ['code' => '130903', 'designation_ar' => 'عين فزة', 'designation_fr' => 'Aïn Fezza', 'designation_en' => 'Ain Fezza'],
        ],
    ],


    [
        'code' => '1310',
        'designation_ar' => 'بني بوسعيد',
        'designation_fr' => 'Beni Boussaid',
        'designation_en' => 'Beni Boussaid',
        'communes' => [
            ['code' => '131001', 'designation_ar' => 'بني بوسعيد', 'designation_fr' => 'Beni Boussaid', 'designation_en' => 'Beni Boussaid'],
            ['code' => '131002', 'designation_ar' => 'عين الكبيرة', 'designation_fr' => 'Ain Kebira', 'designation_en' => 'Ain Kebira'],
        ],
    ],
    [
        'code' => '1311',
        'designation_ar' => 'باب العسة',
        'designation_fr' => 'Bab El Assa',
        'designation_en' => 'Bab El Assa',
        'communes' => [
            ['code' => '131101', 'designation_ar' => 'باب العسة', 'designation_fr' => 'Bab El Assa', 'designation_en' => 'Bab El Assa'],
            ['code' => '131102', 'designation_ar' => 'مسيردة الفواقة', 'designation_fr' => 'Msirda Fouaga', 'designation_en' => 'Msirda Fouaga'],
        ],
    ],
    [
        'code' => '1312',
        'designation_ar' => 'مرسى بن مهيدي',
        'designation_fr' => 'Marsa Ben M\'hidi',
        'designation_en' => 'Marsa Ben M\'hidi',
        'communes' => [
            ['code' => '131201', 'designation_ar' => 'مرسى بن مهيدي', 'designation_fr' => 'Marsa Ben M\'hidi', 'designation_en' => 'Marsa Ben M\'hidi'],
            ['code' => '131202', 'designation_ar' => 'السواحلية', 'designation_fr' => 'Souahlia', 'designation_en' => 'Souahlia'],
        ],
    ],
    [
        'code' => '1313',
        'designation_ar' => 'بني سنوس',
        'designation_fr' => 'Beni Snous',
        'designation_en' => 'Beni Snous',
        'communes' => [
            ['code' => '131301', 'designation_ar' => 'بني سنوس', 'designation_fr' => 'Beni Snous', 'designation_en' => 'Beni Snous'],
            ['code' => '131302', 'designation_ar' => 'عين فتاح', 'designation_fr' => 'Ain Fetah', 'designation_en' => 'Ain Fetah'],
        ],
    ],
    [
        'code' => '1314',
        'designation_ar' => 'بني وارسوس',
        'designation_fr' => 'Beni Ouarsous',
        'designation_en' => 'Beni Ouarsous',
        'communes' => [
            ['code' => '131401', 'designation_ar' => 'بني وارسوس', 'designation_fr' => 'Beni Ouarsous', 'designation_en' => 'Beni Ouarsous'],
            ['code' => '131402', 'designation_ar' => 'عين يوسف', 'designation_fr' => 'Aïn Youcef', 'designation_en' => 'Ain Youcef'],
        ],
    ],
    [
        'code' => '1315',
        'designation_ar' => 'سيدي الجيلالي',
        'designation_fr' => 'Sidi Djillali',
        'designation_en' => 'Sidi Djillali',
        'communes' => [
            ['code' => '131501', 'designation_ar' => 'سيدي الجيلالي', 'designation_fr' => 'Sidi Djillali', 'designation_en' => 'Sidi Djillali'],
            ['code' => '131502', 'designation_ar' => 'عين تالوت', 'designation_fr' => 'Ain Tallout', 'designation_en' => 'Ain Tallout'],
        ],
    ],
    [
        'code' => '1316',
        'designation_ar' => 'بنسكران',
        'designation_fr' => 'Bensekrane',
        'designation_en' => 'Bensekrane',
        'communes' => [
            ['code' => '131601', 'designation_ar' => 'بنسكران', 'designation_fr' => 'Bensekrane', 'designation_en' => 'Bensekrane'],
            ['code' => '131602', 'designation_ar' => 'عين فتاح', 'designation_fr' => 'Ain Fetah', 'designation_en' => 'Ain Fetah'],
        ],
    ],
    [
        'code' => '1317',
        'designation_ar' => 'أولاد ميمون',
        'designation_fr' => 'Ouled Mimoun',
        'designation_en' => 'Ouled Mimoun',
        'communes' => [
            ['code' => '131701', 'designation_ar' => 'أولاد ميمون', 'designation_fr' => 'Ouled Mimoun', 'designation_en' => 'Ouled Mimoun'],
            ['code' => '131702', 'designation_ar' => 'عين غرابة', 'designation_fr' => 'Ain Gharaba', 'designation_en' => 'Ain Gharaba'],
        ],
    ],



];




        foreach ($dairates as $dairaData) {
            // Create daira & attach wilaya_id
            $daira = Daira::create([
                'code'             => $dairaData['code'],
                'designation_ar'   => $dairaData['designation_ar'],
                'designation_fr'   => $dairaData['designation_fr'],
                'designation_en'   => $dairaData['designation_en'],
                'wilaya_id'        => $wilaya->id,
            ]);

            // Create communes & attach daira_id
            foreach ($dairaData['communes'] as $communeData) {
                Commune::create([
                    'code'             => $communeData['code'],
                    'designation_ar'   => $communeData['designation_ar'],
                    'designation_fr'   => $communeData['designation_fr'],
                    'designation_en'   => $communeData['designation_en'],
                    'daira_id'         => $daira->id,
                ]);
            }
        }}
}
