<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Portfolio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat direktori untuk portfolio jika belum ada
        Storage::disk('public')->makeDirectory('portfolios', true);

        // Data siswa dummy
        $students = [
            [
                'nis' => '2024001',
                'name' => 'Budi Susanto',
                'class' => 'XII A',
            ],
            [
                'nis' => '2024002',
                'name' => 'Siti Nurhaliza',
                'class' => 'XII A',
            ],
            [
                'nis' => '2024003',
                'name' => 'Raha Pratama',
                'class' => 'XII B',
            ],
            [
                'nis' => '2024004',
                'name' => 'Eka Widyastuti',
                'class' => 'XII B',
            ],
            [
                'nis' => '2024005',
                'name' => 'Ahmad Fadillah',
                'class' => 'XII C',
            ],
        ];

        // Buat siswa
        $createdStudents = [];
        foreach ($students as $studentData) {
            $createdStudents[] = Student::firstOrCreate(
                ['nis' => $studentData['nis']],
                [
                    'name' => $studentData['name'],
                    'class' => $studentData['class'],
                ]
            );
        }

        // Mapping student ID ke nama dan kelas
        $studentMapping = [
            1 => ['name' => 'Budi Susanto', 'class' => 'XII A'],
            2 => ['name' => 'Siti Nurhaliza', 'class' => 'XII A'],
            3 => ['name' => 'Raha Pratama', 'class' => 'XII B'],
            4 => ['name' => 'Eka Widyastuti', 'class' => 'XII B'],
            5 => ['name' => 'Ahmad Fadillah', 'class' => 'XII C'],
        ];

        // Portfolio dummy data
        $portfolios = [
            [
                'student_id' => 1,
                'student_name' => 'Budi Susanto',
                'student_class' => 'XII A',
                'title' => 'Juara 1 Kompetisi Robot 2024',
                'description' => 'Saya berhasil memenangkan juara 1 kompetisi robot nasional yang diselenggarakan oleh Kementerian Pendidikan. Tim saya mengembangkan robot manipulator dengan teknologi AI untuk otomasi industri.',
                'type' => 'prestasi',
                'verified_status' => 'approved',
                'verified_by' => 1,
                'verified_at' => now()->subDays(5),
            ],
            [
                'student_id' => 1,
                'student_name' => 'Budi Susanto',
                'student_class' => 'XII A',
                'title' => 'Aplikasi Mobile Toko Online',
                'description' => 'Saya mengembangkan aplikasi mobile untuk sistem toko online dengan fitur katalog produk, keranjang belanja, dan integrasi payment gateway. Aplikasi ini telah digunakan oleh 100+ pengguna aktif.',
                'type' => 'karya',
                'verified_status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
            ],
            [
                'student_id' => 2,
                'student_name' => 'Siti Nurhaliza',
                'student_class' => 'XII A',
                'title' => 'Sertifikat Google Cloud Associate Cloud Engineer',
                'description' => 'Saya telah menyelesaikan pelatihan dan lulus ujian sertifikasi Google Cloud Associate Cloud Engineer. Sertifikat ini membuktikan kemampuan saya dalam mengelola infrastruktur cloud dan deployment aplikasi.',
                'type' => 'sertifikat',
                'verified_status' => 'approved',
                'verified_by' => 1,
                'verified_at' => now()->subDays(3),
            ],
            [
                'student_id' => 2,
                'student_name' => 'Siti Nurhaliza',
                'student_class' => 'XII A',
                'title' => 'Website Portfolio Digital',
                'description' => 'Karya saya membuat website portfolio personal yang menampilkan semua proyek dan pengalaman saya. Website dibangun menggunakan HTML5, CSS3, JavaScript, dan React, dengan design responsif untuk semua perangkat.',
                'type' => 'karya',
                'verified_status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
            ],
            [
                'student_id' => 3,
                'student_name' => 'Raha Pratama',
                'student_class' => 'XII B',
                'title' => 'Pemenang Hackathon Tech Innovation 2024',
                'description' => 'Tim saya memenangkan kompetisi hackathon tingkat nasional dengan mengembangkan solusi IoT untuk pertanian cerdas. Produk kami berhasil menarik perhatian investor dan investor bersedia mendanai startup kami.',
                'type' => 'prestasi',
                'verified_status' => 'approved',
                'verified_by' => 1,
                'verified_at' => now()->subDays(10),
            ],
            [
                'student_id' => 3,
                'student_name' => 'Raha Pratama',
                'student_class' => 'XII B',
                'title' => 'Sistem Manajemen Sekolah',
                'description' => 'Saya membuat sistem manajemen sekolah lengkap dengan fitur administrasi siswa, penilaian, presensi, dan komunikasi guru-orang tua. Sistem ini telah diimplementasikan di 3 sekolah dan digunakan oleh ratusan pengguna.',
                'type' => 'karya',
                'verified_status' => 'approved',
                'verified_by' => 1,
                'verified_at' => now()->subDays(7),
            ],
            [
                'student_id' => 4,
                'student_name' => 'Eka Widyastuti',
                'student_class' => 'XII B',
                'title' => 'Sertifikat Microsoft Azure Fundamentals',
                'description' => 'Saya telah menyelesaikan pelatihan Azure Fundamentals dan lulus ujian sertifikasi. Sertifikat ini membuktikan pemahaman saya tentang cloud computing dan layanan-layanan di Azure.',
                'type' => 'sertifikat',
                'verified_status' => 'rejected',
                'verified_by' => 1,
                'verified_at' => now()->subDays(2),
            ],
            [
                'student_id' => 5,
                'student_name' => 'Ahmad Fadillah',
                'student_class' => 'XII C',
                'title' => 'Dashboard Analytics Data Science',
                'description' => 'Saya mengembangkan dashboard analytics menggunakan Python, Pandas, dan Matplotlib untuk visualisasi data penjualan. Dashboard ini membantu manajemen membuat keputusan bisnis berdasarkan data real-time.',
                'type' => 'karya',
                'verified_status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
            ],
        ];

        // Buat portfolio
        foreach ($portfolios as $portfolioData) {
            // Generate fake filename
            $fileExtensions = ['pdf', 'jpg', 'png'];
            $randomExt = $fileExtensions[array_rand($fileExtensions)];
            $fileName = time() . '_' . Str::slug($portfolioData['title']) . '.' . $randomExt;

            // Create dummy file
            Storage::disk('public')->put("portfolios/{$fileName}", 'Dummy file content for testing');

            $portfolioData['file_path'] = "portfolios/{$fileName}";

            Portfolio::create($portfolioData);
        }

        $this->command->info('Portfolio seeder berhasil dijalankan!');
        $this->command->info('Total: ' . count($createdStudents) . ' siswa dan ' . count($portfolios) . ' portfolio dibuat.');
    }
}
