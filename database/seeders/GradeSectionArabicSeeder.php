<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSectionArabicSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ امسحي القديم (باش ما يتكررش)
        DB::table('sections')->delete();
        DB::table('grades')->delete();

        // ✅ الفصول
        $grades = [
            'الصف الأول ابتدائي',
            'الصف الثاني ابتدائي',
            'الصف الثالث ابتدائي',
            'الصف الرابع ابتدائي',
            'الصف الخامس ابتدائي',
            'الصف السادس ابتدائي',
            'الأول إعدادي',
            'الثاني إعدادي',
            'الثالث إعدادي',
            'الأول ثانوي',
            'الثاني ثانوي',
            'الثالث ثانوي',
        ];

        $gradeIds = [];
        foreach ($grades as $g) {
            $gradeIds[] = DB::table('grades')->insertGetId([
                'name' => $g,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ✅ الشعب
        $sectionsNames = ['أول', 'ثاني', 'ثالث'];

        foreach ($gradeIds as $gid) {
            foreach ($sectionsNames as $s) {
                DB::table('sections')->insert([
                    'grade_id' => $gid,
                    'name' => $s,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}