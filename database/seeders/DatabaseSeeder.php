<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use database\seeds\UsersAndNotesSeeder;
//use database\seeds\MenusTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(MenusTableSeeder::class);
        //$this->call(UsersAndNotesSeeder::class);
        /*
        $this->call('UsersAndNotesSeeder');
        $this->call('MenusTableSeeder');
        $this->call('FolderTableSeeder');
        $this->call('ExampleSeeder');
        $this->call('BREADSeeder');
        $this->call('EmailSeeder');
        */

        $this->call([
            UsersAndNotesSeeder::class,
            MenusTableSeeder::class,
            FolderTableSeeder::class,
            ExampleSeeder::class,
            BREADSeeder::class,
            EmailSeeder::class,
        ]);


        // ここはバンド関係のシーダー 登録のために順番変更
        $this->call([
            // バンドの曲の関係の順番
            LabelsTableSeeder::class,
            BandsTableSeeder::class,
            AlbumsTableSeeder::class,
            MusicsTableSeeder::class,
            LabelsTableSeeder::class,



            // 人の関係の順番
            AdminRolesTableSeeder::class,
            UserRolesTableSeeder::class,
            UsersTableSeeder::class,
            BandMembersTableSeeder::class,
            BandMembersLogsTableSeeder::class,

            MusicalInstrumentsTableSeeder::class,

            HallTableSeeder::class,
            EventsTableSeeder::class,

            // その他のシーダー、中間テーブル
            GoodsTypesTableSeeder::class,
            EntriesTableSeeder::class,
            BandGoodsTableSeeder::class,
            GuestReservationsTableSeeder::class,
            PerformanceListsTableSeeder::class,
            ProceedsTableSeeder::class,
            TicketListsTableSeeder::class,

        ]);
    }
}