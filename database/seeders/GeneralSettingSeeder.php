<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('general_settings')->truncate();
        Schema::enableForeignKeyConstraints();

        GeneralSetting::create([
            'maintenance' => 0,
            'app_name'=>"My App",
            'acronym'=>"MY-APP",
            'inaugural_year'=>'2009',
             'phone'=>"043225536",
             'fax'=>"043225536",
             'email'=>"myApp@gmail.com",
             "address_fr"=>"quartier dar el beida about tachfine tlemcen n° 9",
             "address_ar"=>"حي الدار البيضاء جهة تاشفين تلمسان رقم 9",
             "address_en"=>"Dar el Beida district about Tachfine Tlemcen n° 9",
            'youtube' => 'https://www.youtube.com/',
            'facebook' => 'https://www.facebook.com/',
            'github' => 'https://www.github.com/',
            'linkedin' => 'https://www.linkedin.com/',
            'instagram' => 'https://www.instagram.com/',
            'tiktok' => 'https://www.tiktok.com/',
            'twitter' => 'https://www.x.com/',
            'threads' => 'https://www.threads.com/',
            'snapchat' => 'https://www.snapchat.com/',
            'pinterest' => 'https://www.pinterest.com/',
            'reddit' => 'https://www.reddit.com/',
            'telegram' => 'https://t.me/',
            'whatsapp' => 'https://wa.me/',
            'discord' => 'https://discord.gg/',
            'twitch' => 'https://www.twitch.tv/',
            'map'=>'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d408.9991122209878!2d-1.317819613234601!3d34.90662774257976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd78c9036ffc65cd%3A0xabb1e09264a2f332!2sAbou%20Tachefine%2C%20Tlemcen!5e0!3m2!1sfr!2sdz!4v1765879537844!5m2!1sfr!2sdz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
        ]);
    }
}
