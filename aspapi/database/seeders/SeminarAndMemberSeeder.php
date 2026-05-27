<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use App\Models\Seminar;
use App\Models\SeminarQuestion;

class SeminarAndMemberSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────────────────────────────────
        // 1. MEMBER AKTIF
        // ──────────────────────────────────────────────────────────────────────

        $user = User::firstOrCreate(
            ['email' => 'anggota.demo@aspapi.or.id'],
            [
                'name'               => 'Anggota Demo',
                'password'           => Hash::make('password123'),
                'role'               => 'anggota',
                'email_verified'     => true,
                'email_verified_at'  => Carbon::now(),
            ]
        );

        Member::firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name'         => 'Anggota Demo ASPAPI',
                'email'             => 'anggota.demo@aspapi.or.id',
                'phone'             => '081234567890',
                'nik'               => '3201010101010001',
                'birth_place'       => 'Bandung',
                'birth_date'        => '1995-01-01',
                'gender'            => 'L',
                'last_education'    => 'S1',
                'member_type'       => 'biasa',
                'registration_type' => 'baru',
                'claims_old_member' => false,
                'institution'       => 'Universitas Padjadjaran',
                'occupation'        => 'Administrasi Perkantoran',
                'position'          => 'Staff Administrasi',
                'province'          => 'Jawa Barat',
                'city'              => 'Bandung',
                'address'           => 'Jl. Dipatiukur No. 35, Bandung',
                'member_number'     => 'ASPAPI-DEMO-001',
                'biodata_status'    => 'verified',
                'status'            => 'active',
                'dues_paid'         => true,
                'dues_paid_at'      => Carbon::now()->subMonths(1),
                'registered_at'     => Carbon::now()->subMonths(1)->toDateString(),
                'active_until'      => Carbon::now()->addMonths(11)->toDateString(),
            ]
        );

        $this->command->info('✓ Member aktif: anggota.demo@aspapi.or.id / password123');

        // ──────────────────────────────────────────────────────────────────────
        // 2. SEMINAR + SOAL
        // ──────────────────────────────────────────────────────────────────────

        $seminars = [

            // ── SEMINAR 1 ──────────────────────────────────────────────────────
            [
                'seminar' => [
                    'title'         => 'Manajemen Arsip Modern di Era Digital',
                    'category'      => 'Manajemen',
                    'description'   => 'Seminar ini membahas pengelolaan arsip secara modern menggunakan teknologi digital, mulai dari digitalisasi dokumen, sistem penyimpanan berbasis cloud, hingga keamanan data arsip organisasi.',
                    'material_url'  => 'https://drive.google.com/file/d/1BxiMYs0ANF_1cKS0LxcXj5NcG2EXAMPLE/view',
                    'passing_grade' => 70,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'question'       => 'Apa yang dimaksud dengan arsip menurut Undang-Undang Nomor 43 Tahun 2009?',
                        'option_a'       => 'Kumpulan dokumen penting yang disimpan dalam lemari besi',
                        'option_b'       => 'Rekaman kegiatan atau peristiwa dalam berbagai bentuk dan media sesuai dengan perkembangan teknologi',
                        'option_c'       => 'Berkas surat-menyurat yang sudah tidak aktif digunakan',
                        'option_d'       => 'Dokumen yang sudah didigitalisasi dan disimpan di server',
                        'option_e'       => 'Catatan keuangan organisasi yang disimpan secara permanen',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Sistem penyimpanan arsip yang menggunakan urutan abjad disebut?',
                        'option_a'       => 'Sistem nomor',
                        'option_b'       => 'Sistem geografis',
                        'option_c'       => 'Sistem abjad',
                        'option_d'       => 'Sistem subjek',
                        'option_e'       => 'Sistem kronologis',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Proses pemindahan arsip dari arsip aktif ke arsip inaktif disebut?',
                        'option_a'       => 'Penyusutan arsip',
                        'option_b'       => 'Pemindahan arsip',
                        'option_c'       => 'Pemusnahan arsip',
                        'option_d'       => 'Digitalisasi arsip',
                        'option_e'       => 'Akuisisi arsip',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Manakah yang merupakan keuntungan utama sistem arsip digital dibanding arsip fisik?',
                        'option_a'       => 'Biaya pengadaan yang lebih murah',
                        'option_b'       => 'Tidak memerlukan pelatihan khusus untuk pengelolaan',
                        'option_c'       => 'Kemudahan akses, pencarian cepat, dan penghematan ruang penyimpanan',
                        'option_d'       => 'Arsip digital tidak dapat rusak atau hilang',
                        'option_e'       => 'Tidak memerlukan backup data secara berkala',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Apa kepanjangan dari ECM dalam konteks manajemen arsip digital?',
                        'option_a'       => 'Electronic Content Management',
                        'option_b'       => 'Enterprise Content Management',
                        'option_c'       => 'Effective Content Monitoring',
                        'option_d'       => 'Electronic Communication Management',
                        'option_e'       => 'Enterprise Communication Media',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Jadwal Retensi Arsip (JRA) berfungsi untuk?',
                        'option_a'       => 'Menentukan lokasi penyimpanan arsip',
                        'option_b'       => 'Mengatur tata letak lemari arsip di ruangan',
                        'option_c'       => 'Pedoman penentuan jangka simpan dan nasib akhir arsip',
                        'option_d'       => 'Daftar nama pegawai yang bertanggung jawab atas arsip',
                        'option_e'       => 'Panduan pengkodean nomor arsip',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Metadata dalam arsip digital berfungsi sebagai?',
                        'option_a'       => 'Isi utama dari dokumen digital',
                        'option_b'       => 'Informasi tentang dokumen yang membantu pengelolaan dan pencarian',
                        'option_c'       => 'Kata sandi untuk membuka dokumen rahasia',
                        'option_d'       => 'Format file dokumen digital',
                        'option_e'       => 'Nama file yang diberikan saat digitalisasi',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Manakah format file yang paling direkomendasikan untuk arsip digital jangka panjang?',
                        'option_a'       => 'DOCX',
                        'option_b'       => 'XLS',
                        'option_c'       => 'PDF/A',
                        'option_d'       => 'PPT',
                        'option_e'       => 'TXT',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Pemusnahan arsip harus dilakukan dengan?',
                        'option_a'       => 'Langsung dibuang ke tempat sampah',
                        'option_b'       => 'Dibakar tanpa perlu prosedur apapun',
                        'option_c'       => 'Mengikuti prosedur resmi dan mendapat persetujuan pejabat berwenang',
                        'option_d'       => 'Diserahkan kepada pihak daur ulang secara langsung',
                        'option_e'       => 'Disimpan di gudang hingga tidak ada tempat lagi',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Cloud storage dalam manajemen arsip digital berfungsi untuk?',
                        'option_a'       => 'Mencetak dokumen secara otomatis',
                        'option_b'       => 'Menyimpan arsip di server online yang dapat diakses dari mana saja',
                        'option_c'       => 'Mengenkripsi dokumen agar tidak bisa dibaca orang lain',
                        'option_d'       => 'Menghapus arsip yang sudah kadaluarsa secara otomatis',
                        'option_e'       => 'Mengubah format dokumen lama ke format baru',
                        'correct_answer' => 'b',
                    ],
                ],
            ],

            // ── SEMINAR 2 ──────────────────────────────────────────────────────
            [
                'seminar' => [
                    'title'         => 'Komunikasi Profesional dalam Lingkungan Kerja',
                    'category'      => 'Komunikasi',
                    'description'   => 'Seminar ini membahas teknik komunikasi efektif di tempat kerja, termasuk komunikasi verbal dan non-verbal, penulisan surat resmi, komunikasi lintas budaya, serta etika komunikasi dalam organisasi.',
                    'material_url'  => 'https://drive.google.com/file/d/1BxiMYs0ANF_1cKS0LxcXj5NcG2EXAMPLE2/view',
                    'passing_grade' => 75,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'question'       => 'Manakah yang termasuk komunikasi non-verbal?',
                        'option_a'       => 'Surat elektronik',
                        'option_b'       => 'Ekspresi wajah dan gestur tubuh',
                        'option_c'       => 'Presentasi lisan',
                        'option_d'       => 'Laporan tertulis',
                        'option_e'       => 'Diskusi kelompok',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Prinsip 7C dalam komunikasi bisnis yang efektif meliputi?',
                        'option_a'       => 'Clear, Concise, Concrete, Correct, Coherent, Complete, Courteous',
                        'option_b'       => 'Creative, Consistent, Constructive, Clear, Calm, Careful, Careful',
                        'option_c'       => 'Concise, Cooperative, Clear, Communicative, Competitive, Complete, Correct',
                        'option_d'       => 'Clear, Creative, Consistent, Collaborative, Concise, Calm, Correct',
                        'option_e'       => 'Complete, Creative, Correct, Consistent, Calm, Clear, Cooperative',
                        'correct_answer' => 'a',
                    ],
                    [
                        'question'       => 'Hambatan komunikasi yang berasal dari perbedaan persepsi antara pengirim dan penerima pesan disebut?',
                        'option_a'       => 'Hambatan fisik',
                        'option_b'       => 'Hambatan semantik',
                        'option_c'       => 'Hambatan psikologis',
                        'option_d'       => 'Hambatan budaya',
                        'option_e'       => 'Hambatan teknis',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Dalam penulisan surat resmi, bagian yang berisi inti dari maksud surat disebut?',
                        'option_a'       => 'Kepala surat',
                        'option_b'       => 'Pembuka surat',
                        'option_c'       => 'Isi surat',
                        'option_d'       => 'Penutup surat',
                        'option_e'       => 'Lampiran surat',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Active listening (mendengarkan aktif) dalam komunikasi profesional berarti?',
                        'option_a'       => 'Mendengarkan sambil mengerjakan pekerjaan lain',
                        'option_b'       => 'Fokus penuh pada pembicara dan memberikan respons yang sesuai',
                        'option_c'       => 'Mencatat semua yang dikatakan pembicara',
                        'option_d'       => 'Mendengarkan hanya bagian yang dianggap penting',
                        'option_e'       => 'Menunggu giliran berbicara saat orang lain berbicara',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Etika penggunaan email profesional yang benar adalah?',
                        'option_a'       => 'Menggunakan huruf kapital semua agar terlihat tegas',
                        'option_b'       => 'Mengirim email tanpa subjek agar lebih efisien',
                        'option_c'       => 'Menggunakan bahasa informal untuk suasana lebih santai',
                        'option_d'       => 'Menyertakan subjek yang jelas dan menggunakan bahasa formal',
                        'option_e'       => 'Selalu me-reply all pada setiap email yang masuk',
                        'correct_answer' => 'd',
                    ],
                    [
                        'question'       => 'Komunikasi ke atas (upward communication) dalam organisasi adalah?',
                        'option_a'       => 'Komunikasi dari pimpinan kepada bawahan',
                        'option_b'       => 'Komunikasi antar departemen yang setara',
                        'option_c'       => 'Komunikasi dari bawahan kepada pimpinan',
                        'option_d'       => 'Komunikasi dengan pihak eksternal organisasi',
                        'option_e'       => 'Komunikasi melalui media sosial perusahaan',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Teknik feedback sandwich dalam memberikan kritik berarti?',
                        'option_a'       => 'Memberikan kritik secara langsung tanpa basa-basi',
                        'option_b'       => 'Mengapit kritik dengan pujian di awal dan akhir',
                        'option_c'       => 'Memberikan kritik secara tertulis melalui email',
                        'option_d'       => 'Menyampaikan kritik melalui perantara orang ketiga',
                        'option_e'       => 'Menunda kritik hingga ada kesempatan yang tepat',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Presentasi yang efektif harus memperhatikan aspek?',
                        'option_a'       => 'Jumlah slide sebanyak mungkin agar terlihat lengkap',
                        'option_b'       => 'Konten, penyampaian, dan kemampuan menjawab pertanyaan',
                        'option_c'       => 'Menggunakan font dan warna yang beragam',
                        'option_d'       => 'Membacakan semua teks yang ada di slide',
                        'option_e'       => 'Menghindari kontak mata dengan audiens',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Apa yang dimaksud dengan komunikasi lintas budaya (cross-cultural communication)?',
                        'option_a'       => 'Komunikasi menggunakan lebih dari satu bahasa',
                        'option_b'       => 'Komunikasi antara individu dari latar belakang budaya yang berbeda',
                        'option_c'       => 'Komunikasi yang dilakukan di luar negeri',
                        'option_d'       => 'Komunikasi yang menggunakan simbol-simbol budaya tertentu',
                        'option_e'       => 'Komunikasi formal antar perusahaan multinasional',
                        'correct_answer' => 'b',
                    ],
                ],
            ],

            // ── SEMINAR 3 ──────────────────────────────────────────────────────
            [
                'seminar' => [
                    'title'         => 'Otomatisasi Perkantoran dengan Microsoft 365',
                    'category'      => 'Teknologi',
                    'description'   => 'Seminar ini mengulas pemanfaatan Microsoft 365 untuk meningkatkan produktivitas perkantoran, meliputi Word, Excel, PowerPoint, Teams, SharePoint, dan Power Automate untuk otomatisasi alur kerja.',
                    'material_url'  => 'https://drive.google.com/file/d/1BxiMYs0ANF_1cKS0LxcXj5NcG2EXAMPLE3/view',
                    'passing_grade' => 70,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'question'       => 'Fitur di Microsoft Excel yang digunakan untuk merangkum dan menganalisis data dalam jumlah besar secara interaktif adalah?',
                        'option_a'       => 'VLOOKUP',
                        'option_b'       => 'Conditional Formatting',
                        'option_c'       => 'PivotTable',
                        'option_d'       => 'Data Validation',
                        'option_e'       => 'Goal Seek',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Power Automate dalam Microsoft 365 berfungsi untuk?',
                        'option_a'       => 'Membuat presentasi otomatis',
                        'option_b'       => 'Mengotomatiskan alur kerja dan tugas berulang tanpa kode',
                        'option_c'       => 'Mengelola database perusahaan',
                        'option_d'       => 'Membuat laporan keuangan otomatis',
                        'option_e'       => 'Mengirim email massal kepada seluruh karyawan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Microsoft Teams digunakan terutama untuk?',
                        'option_a'       => 'Pengelolaan file dan dokumen perusahaan',
                        'option_b'       => 'Kolaborasi tim melalui chat, rapat video, dan berbagi file',
                        'option_c'       => 'Membuat presentasi yang menarik',
                        'option_d'       => 'Analisis data bisnis secara real-time',
                        'option_e'       => 'Pengelolaan email perusahaan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Fungsi VLOOKUP di Excel digunakan untuk?',
                        'option_a'       => 'Menghitung jumlah data yang memenuhi kriteria tertentu',
                        'option_b'       => 'Mencari nilai dalam kolom pertama tabel dan mengembalikan nilai dari kolom lain',
                        'option_c'       => 'Menggabungkan teks dari beberapa sel',
                        'option_d'       => 'Membuat grafik secara otomatis dari data terpilih',
                        'option_e'       => 'Memfilter data berdasarkan kondisi tertentu',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'SharePoint dalam Microsoft 365 berfungsi sebagai?',
                        'option_a'       => 'Aplikasi presentasi berbasis cloud',
                        'option_b'       => 'Platform kolaborasi dan manajemen dokumen bersama dalam intranet',
                        'option_c'       => 'Alat pembuatan database online',
                        'option_d'       => 'Sistem manajemen email organisasi',
                        'option_e'       => 'Aplikasi pencatatan keuangan perusahaan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Fitur Track Changes di Microsoft Word berguna untuk?',
                        'option_a'       => 'Melacak perubahan ukuran file dokumen',
                        'option_b'       => 'Mencatat semua perubahan yang dilakukan pada dokumen beserta identitas pengubah',
                        'option_c'       => 'Mengatur versi dokumen secara otomatis',
                        'option_d'       => 'Menyimpan riwayat unduhan dokumen',
                        'option_e'       => 'Membatasi hak akses pengeditan dokumen',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Apa keunggulan menyimpan dokumen di OneDrive dibanding hard drive lokal?',
                        'option_a'       => 'File tersimpan lebih aman dari virus',
                        'option_b'       => 'Kapasitas penyimpanan tidak terbatas',
                        'option_c'       => 'Dapat diakses dari perangkat manapun dan mendukung kolaborasi real-time',
                        'option_d'       => 'Tidak memerlukan koneksi internet untuk mengakses file',
                        'option_e'       => 'Proses penyimpanan lebih cepat dari SSD',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Conditional Formatting di Excel digunakan untuk?',
                        'option_a'       => 'Mengatur format sel berdasarkan kondisi atau nilai tertentu',
                        'option_b'       => 'Membuat rumus yang berjalan secara kondisional',
                        'option_c'       => 'Mengatur tampilan grafik berdasarkan data',
                        'option_d'       => 'Memproteksi sel tertentu dari perubahan',
                        'option_e'       => 'Mengimpor data dari sumber eksternal',
                        'correct_answer' => 'a',
                    ],
                    [
                        'question'       => 'Microsoft Forms digunakan untuk?',
                        'option_a'       => 'Membuat template dokumen Word secara otomatis',
                        'option_b'       => 'Membuat survei, kuis, dan formulir online dengan mudah',
                        'option_c'       => 'Mengelola kalender dan jadwal rapat',
                        'option_d'       => 'Membuat invoice dan laporan keuangan',
                        'option_e'       => 'Mengelola daftar kontak organisasi',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Apa yang dimaksud dengan co-authoring di Microsoft 365?',
                        'option_a'       => 'Fitur untuk membuat dokumen secara offline',
                        'option_b'       => 'Kemampuan beberapa pengguna mengedit dokumen yang sama secara bersamaan',
                        'option_c'       => 'Fitur untuk mengatur hak akses dokumen',
                        'option_d'       => 'Proses menyimpan dokumen ke berbagai format',
                        'option_e'       => 'Kemampuan menggabungkan beberapa dokumen menjadi satu',
                        'correct_answer' => 'b',
                    ],
                ],
            ],

            // ── SEMINAR 4 ──────────────────────────────────────────────────────
            [
                'seminar' => [
                    'title'         => 'Manajemen Waktu dan Produktivitas Kerja',
                    'category'      => 'Manajemen',
                    'description'   => 'Seminar ini membahas strategi pengelolaan waktu yang efektif di lingkungan kerja, teknik prioritisasi tugas, mengatasi prokrastinasi, dan membangun kebiasaan kerja produktif yang berkelanjutan.',
                    'material_url'  => 'https://drive.google.com/file/d/1BxiMYs0ANF_1cKS0LxcXj5NcG2EXAMPLE4/view',
                    'passing_grade' => 70,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'question'       => 'Matriks Eisenhower membagi tugas berdasarkan?',
                        'option_a'       => 'Tingkat kesulitan dan waktu pengerjaan',
                        'option_b'       => 'Urgensi dan tingkat kepentingan',
                        'option_c'       => 'Prioritas dan biaya yang dibutuhkan',
                        'option_d'       => 'Kompleksitas dan jumlah orang yang terlibat',
                        'option_e'       => 'Dampak jangka pendek dan jangka panjang',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Teknik Pomodoro dalam manajemen waktu bekerja dengan cara?',
                        'option_a'       => 'Bekerja 8 jam tanpa istirahat untuk menyelesaikan tugas',
                        'option_b'       => 'Bekerja fokus 25 menit, lalu istirahat 5 menit secara bergantian',
                        'option_c'       => 'Mengelompokkan tugas sejenis dan mengerjakannya sekaligus',
                        'option_d'       => 'Mendelegasikan semua tugas kepada tim',
                        'option_e'       => 'Bekerja di pagi hari saja saat kondisi paling segar',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Prokrastinasi dalam konteks produktivitas kerja berarti?',
                        'option_a'       => 'Mengerjakan banyak tugas sekaligus',
                        'option_b'       => 'Menunda-nunda pekerjaan meskipun tahu dampak negatifnya',
                        'option_c'       => 'Bekerja terlalu keras hingga kelelahan',
                        'option_d'       => 'Memprioritaskan tugas yang paling mudah terlebih dahulu',
                        'option_e'       => 'Merencanakan pekerjaan terlalu detail',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Prinsip Pareto (80/20) dalam produktivitas menyatakan bahwa?',
                        'option_a'       => '80% waktu dihabiskan untuk 20% tugas paling sulit',
                        'option_b'       => '80% hasil berasal dari 20% usaha yang paling penting',
                        'option_c'       => '80% karyawan produktif di 20% waktu kerja mereka',
                        'option_d'       => '80% tugas harus selesai dalam 20% tenggat waktu',
                        'option_e'       => '80% masalah berasal dari 20% karyawan yang tidak produktif',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Time blocking adalah teknik manajemen waktu yang?',
                        'option_a'       => 'Memblokir semua notifikasi saat bekerja',
                        'option_b'       => 'Membagi hari kerja menjadi blok waktu khusus untuk tugas tertentu',
                        'option_c'       => 'Membatasi jam kerja maksimal 8 jam per hari',
                        'option_d'       => 'Menghindari pertemuan yang tidak produktif',
                        'option_e'       => 'Menggunakan timer untuk setiap aktivitas kerja',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Burnout dalam konteks kerja terjadi karena?',
                        'option_a'       => 'Bekerja di lingkungan yang terlalu dingin',
                        'option_b'       => 'Kelelahan fisik, mental, dan emosional akibat stres kerja berkepanjangan',
                        'option_c'       => 'Terlalu banyak cuti dan tidak produktif',
                        'option_d'       => 'Kurangnya motivasi untuk bekerja dari awal',
                        'option_e'       => 'Konflik dengan rekan kerja yang tidak terselesaikan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'SMART goals dalam perencanaan kerja adalah tujuan yang?',
                        'option_a'       => 'Simple, Manageable, Affordable, Reliable, Timely',
                        'option_b'       => 'Specific, Measurable, Achievable, Relevant, Time-bound',
                        'option_c'       => 'Strategic, Meaningful, Actionable, Reasonable, Trackable',
                        'option_d'       => 'Structured, Monitored, Achievable, Realistic, Timed',
                        'option_e'       => 'Systematic, Motivating, Accountable, Reachable, Transparent',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Deep work menurut Cal Newport adalah?',
                        'option_a'       => 'Bekerja di malam hari saat suasana lebih tenang',
                        'option_b'       => 'Kemampuan fokus tanpa gangguan pada tugas kognitif yang menantang',
                        'option_c'       => 'Metode kerja dengan menyelam ke detail teknis pekerjaan',
                        'option_d'       => 'Bekerja keras lebih dari 10 jam per hari',
                        'option_e'       => 'Kolaborasi mendalam dengan tim lintas departemen',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Delegasi tugas yang efektif memerlukan?',
                        'option_a'       => 'Mendelegasikan semua tugas tanpa terkecuali',
                        'option_b'       => 'Memilih tugas yang tepat, orang yang tepat, dan memberikan instruksi yang jelas',
                        'option_c'       => 'Hanya mendelegasikan tugas yang tidak penting',
                        'option_d'       => 'Selalu mengawasi setiap detail pekerjaan yang didelegasikan',
                        'option_e'       => 'Mendelegasikan hanya kepada karyawan senior',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Getting Things Done (GTD) adalah metode produktivitas yang fokus pada?',
                        'option_a'       => 'Bekerja lebih cepat dengan teknologi terbaru',
                        'option_b'       => 'Mengeluarkan semua tugas dari pikiran ke sistem eksternal yang terpercaya',
                        'option_c'       => 'Mengurangi jumlah tugas hingga hanya yang paling penting',
                        'option_d'       => 'Meditasi sebelum memulai pekerjaan setiap hari',
                        'option_e'       => 'Mengerjakan tugas terbesar dan terberat terlebih dahulu',
                        'correct_answer' => 'b',
                    ],
                ],
            ],

            // ── SEMINAR 5 ──────────────────────────────────────────────────────
            [
                'seminar' => [
                    'title'         => 'Layanan Prima dan Pelayanan Pelanggan',
                    'category'      => 'Pelayanan',
                    'description'   => 'Seminar ini membahas konsep dan penerapan layanan prima (service excellence) dalam organisasi, termasuk standar pelayanan, penanganan keluhan, komunikasi dengan pelanggan, dan membangun kepuasan serta loyalitas pelanggan.',
                    'material_url'  => 'https://drive.google.com/file/d/1BxiMYs0ANF_1cKS0LxcXj5NcG2EXAMPLE5/view',
                    'passing_grade' => 75,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'question'       => 'Layanan prima (service excellence) didefinisikan sebagai?',
                        'option_a'       => 'Pelayanan yang diberikan hanya kepada pelanggan VIP',
                        'option_b'       => 'Pelayanan yang memenuhi atau melampaui harapan pelanggan',
                        'option_c'       => 'Pelayanan dengan harga terjangkau dan cepat',
                        'option_d'       => 'Pelayanan yang dilakukan oleh petugas berpengalaman',
                        'option_e'       => 'Pelayanan minimal yang sesuai standar operasional',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Konsep A6 dalam layanan prima meliputi?',
                        'option_a'       => 'Ability, Attitude, Attention, Action, Appearance, Accountability',
                        'option_b'       => 'Attitude, Attention, Action, Ability, Appearance, Accuracy',
                        'option_c'       => 'Ability, Attitude, Attention, Appearance, Accountability, Accuracy',
                        'option_d'       => 'Attitude, Attention, Appearance, Action, Ability, Agility',
                        'option_e'       => 'Accuracy, Ability, Agility, Attitude, Attention, Action',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Langkah pertama yang tepat dalam menangani keluhan pelanggan adalah?',
                        'option_a'       => 'Memberikan solusi secepatnya tanpa mendengarkan',
                        'option_b'       => 'Meminta pelanggan mengisi formulir keluhan terlebih dahulu',
                        'option_c'       => 'Mendengarkan keluhan dengan penuh perhatian dan empati',
                        'option_d'       => 'Meminta maaf berulang kali tanpa tindak lanjut',
                        'option_e'       => 'Mengarahkan pelanggan ke departemen lain yang berwenang',
                        'correct_answer' => 'c',
                    ],
                    [
                        'question'       => 'Customer satisfaction index (CSI) digunakan untuk?',
                        'option_a'       => 'Mengukur jumlah pelanggan baru setiap bulan',
                        'option_b'       => 'Mengukur tingkat kepuasan pelanggan terhadap produk atau layanan',
                        'option_c'       => 'Menghitung pendapatan yang dihasilkan dari setiap pelanggan',
                        'option_d'       => 'Mengidentifikasi pelanggan yang berpotensi churn',
                        'option_e'       => 'Menentukan harga produk berdasarkan persepsi pelanggan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Service recovery adalah?',
                        'option_a'       => 'Proses merekrut tenaga pelayanan baru',
                        'option_b'       => 'Upaya memperbaiki layanan yang gagal untuk memulihkan kepuasan pelanggan',
                        'option_c'       => 'Program pelatihan untuk meningkatkan kualitas pelayanan',
                        'option_d'       => 'Pemulihan data pelanggan yang hilang',
                        'option_e'       => 'Prosedur keselamatan dalam layanan darurat',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Net Promoter Score (NPS) mengukur?',
                        'option_a'       => 'Jumlah pelanggan yang melakukan pembelian ulang',
                        'option_b'       => 'Kemungkinan pelanggan merekomendasikan produk/layanan kepada orang lain',
                        'option_c'       => 'Tingkat kepuasan pelanggan secara keseluruhan',
                        'option_d'       => 'Jumlah keluhan yang berhasil diselesaikan',
                        'option_e'       => 'Kecepatan rata-rata penanganan keluhan pelanggan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Empati dalam pelayanan pelanggan berarti?',
                        'option_a'       => 'Setuju dengan semua yang dikatakan pelanggan',
                        'option_b'       => 'Memahami dan merasakan perasaan serta perspektif pelanggan',
                        'option_c'       => 'Memberikan diskon kepada pelanggan yang tidak puas',
                        'option_d'       => 'Mempercepat proses pelayanan untuk menghemat waktu pelanggan',
                        'option_e'       => 'Selalu tersenyum saat melayani pelanggan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Standard Operating Procedure (SOP) dalam pelayanan berguna untuk?',
                        'option_a'       => 'Membatasi kreativitas petugas dalam memberikan pelayanan',
                        'option_b'       => 'Memastikan konsistensi kualitas layanan dan sebagai panduan kerja',
                        'option_c'       => 'Mengurangi jumlah petugas yang dibutuhkan',
                        'option_d'       => 'Mempercepat proses pelayanan secara otomatis',
                        'option_e'       => 'Menentukan tarif pelayanan yang seragam',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Customer lifetime value (CLV) adalah?',
                        'option_a'       => 'Usia rata-rata pelanggan yang menggunakan produk',
                        'option_b'       => 'Total nilai bersih yang diharapkan dari pelanggan selama hubungan bisnis berlangsung',
                        'option_c'       => 'Nilai kepuasan pelanggan sepanjang masa',
                        'option_d'       => 'Jumlah interaksi pelanggan dengan perusahaan',
                        'option_e'       => 'Biaya yang dikeluarkan untuk mempertahankan satu pelanggan',
                        'correct_answer' => 'b',
                    ],
                    [
                        'question'       => 'Dalam konteks pelayanan publik, asas transparansi berarti?',
                        'option_a'       => 'Pelayanan diberikan secara gratis kepada semua masyarakat',
                        'option_b'       => 'Informasi mengenai prosedur, persyaratan, dan biaya dapat diakses dengan mudah oleh publik',
                        'option_c'       => 'Petugas pelayanan wajib melaporkan semua kegiatan kepada atasan',
                        'option_d'       => 'Semua dokumen pelayanan harus dipublikasikan di media massa',
                        'option_e'       => 'Pelayanan dilakukan tanpa memandang status sosial pemohon',
                        'correct_answer' => 'b',
                    ],
                ],
            ],
        ];

        foreach ($seminars as $idx => $data) {
            $seminar = Seminar::firstOrCreate(
                ['title' => $data['seminar']['title']],
                $data['seminar']
            );

            $sortOrder = 1;
            foreach ($data['questions'] as $q) {
                SeminarQuestion::firstOrCreate(
                    [
                        'seminar_id' => $seminar->id,
                        'question'   => $q['question'],
                    ],
                    array_merge($q, [
                        'seminar_id' => $seminar->id,
                        'sort_order' => $sortOrder++,
                    ])
                );
            }

            $jumlahSoal = $sortOrder - 1;
            $this->command->info("✓ Seminar " . ($idx + 1) . ": {$seminar->title} ({$jumlahSoal} soal)");
        }

        $this->command->newLine();
        $this->command->info('════════════════════════════════════════');
        $this->command->info('  Seeder selesai!');
        $this->command->info('  Login member: anggota.demo@aspapi.or.id');
        $this->command->info('  Password    : password123');
        $this->command->info('════════════════════════════════════════');
    }
}