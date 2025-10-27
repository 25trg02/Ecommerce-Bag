<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlImportSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt kiểm tra khoá ngoại & đảm bảo clean dữ liệu trước khi import
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // (Tuỳ chọn) Xoá sạch các bảng lõi trước khi import SQL (nếu bạn muốn dữ liệu trong file SQL là nguồn sự thật)
        foreach (
            [
                'order_items',
                'orders',
                'products',
                'categories',
                'wishlists',
                'coupons',
                'sessions',
                'job_batches',
                'failed_jobs',
                'jobs',
                'cache_locks',
                'cache',
                'users',
            ] as $tbl
        ) {
            try {
                DB::table($tbl)->truncate();
            } catch (\Throwable $e) {
            }
        }

        // Đọc & chạy file SQL (nguyên văn)
        $path = database_path('seeders/sql/ecommerce2025.sql');
        if (!File::exists($path)) {
            $this->command->error("Không tìm thấy file: {$path}");
            return;
        }

        // Một số dump có chứa DELIMITER/trigger. Ở đây file của bạn là các câu lệnh thuần -> DB::unprepared xử lý tốt.
        $sql = File::get($path);

        // Loại các phần comment MySQL (giúp giảm lỗi với một số host)
        $sql = preg_replace('/^--.*$/m', '', $sql);

        // Bật lại kiểm tra khoá ngoại sau khi import
        DB::unprepared($sql);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('✅ Đã import dữ liệu từ ecommerce2025.sql');
    }
}
