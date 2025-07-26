<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\PartnersTypes;
use App\Models\Category;
use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $start = now();
        dump('seeding: categories');
        Category::factory(5)->create();
        dump('seeding: partner types');
        $this->createPartnersTypes();
        dump('seeding: courts types');
        $this->createCourtsTypes();
        dump('seeding: trainers types');
        $this->createTrainersTypes();
        dump('seeding: lessons types');
        $this->createLessonsTypes();
        dump('seeding: slots duration');
        $this->createSlotsDurations();
        dump($start->diffForHumans());

        Partner::factory()->create([
            'username' => 's',
            'email' => 's@s.com',
            'phone' => '0911111111',
            'type' => PartnersTypes::stadium->name,
        ]);

        Partner::factory()->create([
            'username' => 'a',
            'email' => 'a@a.com',
            'phone' => '0911111112',
            'type' => PartnersTypes::trainer->name,
        ]);
    }

    private function createPartnersTypes()
    {
        if (DB::table('partners_types')->count() === 0) {
            return DB::table('partners_types')->insert([
                ['name' => 'stadium'],
                ['name' => 'trainer'],
            ]);
        }
    }

    private function createCourtsTypes()
    {
        if (DB::table('courts_types')->count() === 0) {
            return DB::table('courts_types')->insert([
                ['name' => 'football'],
                ['name' => 'basketball'],
                ['name' => 'swimming'],
                ['name' => 'billiards'],
                ['name' => 'events'],
            ]);
        }
    }

    private function createTrainersTypes()
    {
        if (DB::table('trainers_types')->count() === 0) {
            return DB::table('trainers_types')->insert([
                ['name' => 'sport'],
                ['name' => 'yoga'],
                ['name' => 'musical'],
                ['name' => 'singing'],
                ['name' => 'language'],
            ]);
        }
    }

    private function createLessonsTypes()
    {
        if (DB::table('courses_types')->count() === 0) {
            return DB::table('courses_types')->insert([
                ['name' => 'sport'],
                ['name' => 'yoga'],
                ['name' => 'musical'],
                ['name' => 'singing'],
                ['name' => 'language'],
            ]);
        }
    }

    private function createSlotsDurations()
    {
        if (DB::table('slots_durations')->count() === 0) {
            return DB::table('slots_durations')->insert([
                ['unit' => 'minute', 'value' => 30],
                ['unit' => 'minute', 'value' => 60],
                ['unit' => 'minute', 'value' => 90],
                ['unit' => 'minute', 'value' => 120],
            ]);
        }
    }
}
