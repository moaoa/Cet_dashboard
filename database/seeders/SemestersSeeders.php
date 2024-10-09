<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemestersSeeders extends Seeder
{

    public function run(): void
    {
        $semesters = [
            'السيمستر الاول',
            'السيمستر الثاني',
            'السيمستر الثالث',
            'السيمستر الرابع',
            'السيمستر الخامس',
            'السيمستر السادس',
            'السيمستر السابع',
            'السيمستر الثامن',
        ];
        
        $counter = 1;
        
        foreach ($semesters as $index => $semester) {
            if ($index >= 2) {
                for ($i = 1; $i <= 3; $i++) {
                    Semester::create([
                        'name' => $semester,
                        'order' => $counter,
                        'major' => $i,
                    ]);
                }
            } else {
                Semester::create([
                    'name' => $semester,
                    'order' => $counter,
                    'major' => 0,
                ]);
            }
            $counter++;
        }
    }
}
