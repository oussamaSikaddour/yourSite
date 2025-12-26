<?php

namespace Database\Seeders;

use App\Models\Hero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('heros')->truncate();
        Schema::enableForeignKeyConstraints();
        Hero::create([
            'title_ar' => 'عنوان Hero',
            'title_fr' => 'Hero Title',
            'title_en' => 'Hero Titre',
            'sub_title_ar' => 'عنوان فرعي Hero',
            'sub_title_fr' => "Sous-titre Hero",
            'sub_title_en' => "Sub-title Hero",
            'introduction_fr' => "Le lorem ipsum est, en imprimerie, une suite de mots sans signification utilisée à titre provisoire pour calibrer une mise en page, le texte définitif venant remplacer le faux-texte dès qu'il est prêt ou que la mise en page est achevée. Généralement, on utilise un texte en faux latin, le Lorem ipsum ou Lipsum.",
            'introduction_ar' => "في الطباعة، يُطلق على لوريم إيبسوم اسم سلسلة من الكلمات عديمة المعنى تُستخدم مؤقتًا لمعايرة الخط، حيث يحل النص النهائي محل النص التقليدي بمجرد جهوزيته أو اكتمال الخط. عادةً، يُستخدم نص لاتيني تقليدي، لوريم إيبسوم أو ليبسوم.",
            'introduction_en' => "Lorem Ipsum is, in printing, a string of meaningless words used temporarily to calibrate a typeface, with the final text replacing the dummy text as soon as it is ready or the typeface is complete. Generally, a dummy Latin text, Lorem Ipsum or Lipsum, is used.",
            'primary_call_to_action_fr' => "CTA principal ",
            'primary_call_to_action_ar' => "الدعوة الرئيسية ",
            'primary_call_to_action_en' => "Primary CTA",
            "secondary_call_to_action_fr" => "CTA secondaire",
            "secondary_call_to_action_en" => "Secondary CTA",
            "secondary_call_to_action_ar" => "الدعوة الثانوية",
        ]);
    }
}
