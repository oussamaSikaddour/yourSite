<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('banks')->truncate();
        Schema::enableForeignKeyConstraints();

        $banks = [
            [
                "code" => "001",
                "designation_fr" => "Banque Nationale d'Algerie",
                "designation_ar" => "البنك الوطني الجزائري", // Add Arabic description
                "designation_en" => "National Bank of Algeria", // Add English description
                "acronym" => "BNA"
            ],
            [
                "code" => "002",
                "designation_fr" => "Banque Exterieur d'Algerie",
                "designation_ar" => "بنك الجزائر الخارجي", // Add Arabic description
                "designation_en" => "Exterior Bank of Algeria", // Add English description
                "acronym" => "BEA"
            ],
            [
                "code" => "003",
                "designation_fr" => "Banque Algerienne Developp. Rural",
                "designation_ar" => "البنك الجزائري للتنمية الريفية", // Add Arabic description
                "designation_en" => "Algerian Bank for Rural Development", // Add English description
                "acronym" => "BADR"
            ],
            [
                "code" => "004",
                "designation_fr" => "CREDIT POPULAIRE D'ALGERIE",
                "designation_ar" => "البنك الشعبي الجزائري", // Add Arabic description
                "designation_en" => "Popular Credit of Algeria", // Add English description
                "acronym" => "CPA"
            ],
            [
                "code" => "005",
                "designation_fr" => "Banque Developpement Local",
                "designation_ar" => "بنك التنمية المحلية", // Add Arabic description
                "designation_en" => "Local Development Bank", // Add English description
                "acronym" => "BDL"
            ],
            [
                "code" => "006",
                "designation_fr" => "El Baraka",
                "designation_ar" => "البركة", // Add Arabic description
                "designation_en" => "El Baraka", // Add English description
                "acronym" => "BARAKA"
            ],
            [
                "code" => "007",
                "designation_fr" => "Centre des Cheques Postaux",
                "designation_ar" => "مركز الشيكات البريدية", // Add Arabic description
                "designation_en" => "Postal Checks Center", // Add English description
                "acronym" => "C.C.P"
            ],
            [
                "code" => "008",
                "designation_fr" => "Trésor Central",
                "designation_ar" => "الخزينة المركزية", // Add Arabic description
                "designation_en" => "Central Treasury", // Add English description
                "acronym" => "TRES"
            ],
            [
                "code" => "010",
                "designation_fr" => "Centre National de Mutualité Agricole",
                "designation_ar" => "المركز الوطني للتأمين الزراعي", // Add Arabic description
                "designation_en" => "National Center for Agricultural Mutualism", // Add English description
                "acronym" => "CNMA"
            ],
            [
                "code" => "011",
                "designation_fr" => "Caisse nationale d'épargne et de prévoyance",
                "designation_ar" => "الصندوق الوطني للتوفير والاحتياط", // Add Arabic description
                "designation_en" => "National Savings and Provident Fund", // Add English description
                "acronym" => "CNEP"
            ],
            [
                "code" => "012",
                "designation_fr" => "City Bank",
                "designation_ar" => "سيتي بنك", // Add Arabic description
                "designation_en" => "City Bank", // Add English description
                "acronym" => "CITYB"
            ],
            [
                "code" => "014",
                "designation_fr" => "A B C CORP.",
                "designation_ar" => "ايه بي سي كورب", // Add Arabic description
                "designation_en" => "A B C CORP.", // Add English description
                "acronym" => "ABCC"
            ],
            [
                "code" => "020",
                "designation_fr" => "NATEXIS",
                "designation_ar" => "ناتيكس", // Add Arabic description
                "designation_en" => "NATEXIS", // Add English description
                "acronym" => "NATEXIS"
            ],
            [
                "code" => "021",
                "designation_fr" => "SOCIETE GENERALE Algerie",
                "designation_ar" => "سوسيتيه جنرال الجزائر", // Add Arabic description
                "designation_en" => "Societe Generale Algeria", // Add English description
                "acronym" => "SGA"
            ],
            [
                "code" => "026",
                "designation_fr" => "ARAB BANK PLC",
                "designation_ar" => "البنك العربي", // Add Arabic description
                "designation_en" => "Arab Bank PLC", // Add English description
                "acronym" => "ABP"
            ],
            [
                "code" => "027",
                "designation_fr" => "Banque nationale de Paris",
                "designation_ar" => "بنك باريس الوطني", // Add Arabic description
                "designation_en" => "National Bank of Paris", // Add English description
                "acronym" => "BNP PARIBAS"
            ],
            [
                "code" => "029",
                "designation_fr" => "Trust Bank",
                "designation_ar" => "ترست بنك", // Add Arabic description
                "designation_en" => "Trust Bank", // Add English description
                "acronym" => "TRUST BANK"
            ],
            [
                "code" => "031",
                "designation_fr" => "HOUSING BANK AG",
                "designation_ar" => "بنك الإسكان", // Add Arabic description
                "designation_en" => "Housing Bank AG", // Add English description
                "acronym" => "HBAG"
            ],
            [
                "code" => "032",
                "designation_fr" => "ALGERIA GULF BANK",
                "designation_ar" => "بنك الخليج الجزائري", // Add Arabic description
                "designation_en" => "Algeria Gulf Bank", // Add English description
                "acronym" => "AGB"
            ],
            [
                "code" => "035",
                "designation_fr" => "Fransabank El Djazaïr",
                "designation_ar" => "فرانس بنك الجزائر", // Add Arabic description
                "designation_en" => "Fransabank Algeria", // Add English description
                "acronym" => "FB"
            ],
            [
                "code" => "036",
                "designation_fr" => "Crédit agricole Corporate and Investment Bank",
                "designation_ar" => "البنك الزراعي للشركات والاستثمار", // Add Arabic description
                "designation_en" => "Agricultural Credit Corporate and Investment Bank", // Add English description
                "acronym" => "CALYON"
            ],
            [
                "code" => "037",
                "designation_fr" => "Hong Kong and Shanghai Banking Corporation Algeria",
                "designation_ar" => "بنك هونغ كونغ وشنغهاي الجزائر", // Add Arabic description
                "designation_en" => "HSBC Algeria", // Add English description
                "acronym" => "HSBCA"
            ],
            [
                "code" => "038",
                "designation_fr" => "AL SALAM BANK ALGERIA",
                "designation_ar" => "بنك السلام الجزائر", // Add Arabic description
                "designation_en" => "Al Salam Bank Algeria", // Add English description
                "acronym" => "ASBA"
            ],
            [
                "code" => "111",
                "designation_fr" => "Banque d'Algerie",
                "designation_ar" => "بنك الجزائر", // Add Arabic description
                "designation_en" => "Bank of Algeria", // Add English description
                "acronym" => "BA"
            ],
        ];

        collect($banks)->each(function ($bank) {
            Bank::create($bank);
        });
    }
}
