<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Import model User
use App\Models\Category; // Import model Category
use Carbon\Carbon;

class TodosSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 2;
        $categoryIds = Category::pluck('id')->toArray();

        $todos = [
            [
                'category_id' => 1,
                'title' => 'Pekerjaan 1',
                'description' => 'Deskripsi Pekerjaan 1',
                'due_date' => Carbon::now()->addDays(7),
                'priority' => 'high',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 1,
                'title' => 'Pekerjaan 2',
                'description' => 'Deskripsi Pekerjaan 2',
                'due_date' => Carbon::now()->addDays(14),
                'priority' => 'medium',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 1,
                'title' => 'Pekerjaan 3',
                'description' => 'Deskripsi Pekerjaan 3',
                'due_date' => Carbon::now()->addDays(21),
                'priority' => 'low',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 2,
                'title' => 'Akademik 1',
                'description' => 'Deskripsi Akademik 1',
                'due_date' => Carbon::now()->addDays(5),
                'priority' => 'high',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 2,
                'title' => 'Akademik 2',
                'description' => 'Deskripsi Akademik 2',
                'due_date' => Carbon::now()->addDays(10),
                'priority' => 'medium',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 2,
                'title' => 'Akademik 3',
                'description' => 'Deskripsi Akademik 3',
                'due_date' => Carbon::now()->addDays(15),
                'priority' => 'low',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 3,
                'title' => 'Pribadi 1',
                'description' => 'Deskripsi Pribadi 1',
                'due_date' => Carbon::now()->addDays(3),
                'priority' => 'high',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 3,
                'title' => 'Pribadi 2',
                'description' => 'Deskripsi Pribadi 2',
                'due_date' => Carbon::now()->addDays(6),
                'priority' => 'medium',
                'status' => 'incomplete',
            ],
            [
                'category_id' => 3,
                'title' => 'Pribadi 3',
                'description' => 'Deskripsi Pribadi 3',
                'due_date' => Carbon::now()->addDays(9),
                'priority' => 'low',
                'status' => 'incomplete',
            ],
        ];

        foreach ($todos as $todo) {
            $todo['user_id'] = $userId;
            DB::table('todos')->insert($todo);
        }
    }
}
