<?php

namespace Database\Seeders;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClipCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = User::first() ?? User::factory()->create([
            'name' => 'Admin Clipper',
            'email' => 'admin@clipper.com',
            'role' => 'admin',
        ]);

        $campaigns = [
            [
                'title' => 'Review Aplikasi Web Clipper AI',
                'description' => 'Kampanye video pendek mengulas fitur-fitur unggulan dan kemudahan penggunaan Web Clipper AI di platform TikTok dan Instagram Reels.',
                'brief' => "1. Durasi video minimal 30 detik.\n2. Tampilkan dashboard clipper dan proses withdraw komisi.\n3. Gunakan hashtag #WebClipperAI #ClipperIndonesia.\n4. Cantumkan link affiliate atau referral di bio.",
                'source_url' => 'https://drive.google.com/drive/folders/sample-clipper-materials-1',
                'commission_amount' => 5000,
                'view_threshold' => 1000,
                'view_max' => 100000,
                'clipper_limit' => 100,
                'start_at' => Carbon::parse('2026-09-15 00:00:00'),
                'end_at' => Carbon::parse('2026-12-25 23:59:59'),
                'status' => CampaignStatus::Active,
                'created_by' => $creator->id,
            ],
            [
                'title' => 'TikTok Clip Tips Finansial & Bisnis',
                'description' => 'Memotong dan mengedit bagian paling berbobot dari podcast finansial untuk dijadikan konten edukasi menarik.',
                'brief' => "1. Fokus pada tips budgeting dan investasi pemula.\n2. Gunakan subtitle animasi yang menarik.\n3. Kualitas audio harus jernih dan bebas noise.",
                'source_url' => 'https://drive.google.com/drive/folders/sample-clipper-materials-2',
                'commission_amount' => 25000,
                'view_threshold' => 10000,
                'view_max' => 200000,
                'clipper_limit' => 150,
                'start_at' => Carbon::parse('2026-10-01 00:00:00'),
                'end_at' => Carbon::parse('2026-12-15 23:59:59'),
                'status' => CampaignStatus::Active,
                'created_by' => $creator->id,
            ],
            [
                'title' => 'Podcast Highlight Inspiring Talks',
                'description' => 'Klip highlight wawancara inspiratif tokoh sukses dengan hook 3 detik pertama yang kuat.',
                'brief' => "1. Durasi 45-60 detik format 9:16.\n2. Sertakan watermark resmi di sudut kanan atas.\n3. Jangan memotong perkataan di luar konteks.",
                'source_url' => 'https://youtube.com/watch?v=sample-video-id',
                'commission_amount' => 15000,
                'view_threshold' => 5000,
                'view_max' => 100000,
                'clipper_limit' => 100,
                'start_at' => Carbon::parse('2026-08-10 00:00:00'),
                'end_at' => Carbon::parse('2026-09-10 23:59:59'),
                'status' => CampaignStatus::Completed,
                'created_by' => $creator->id,
            ],
            [
                'title' => 'Gaming Moments Mobile Legends Ep. 4',
                'description' => 'Kompilasi momen epic comeback dan savage dari turnamen terbaru dengan editing jedag-jedug khas komunitas.',
                'brief' => "1. Backsound bebas copyright (No DMCA).\n2. Transisi dinamis mengikuti beat lagu.\n3. Resolusi minimal 1080p 60fps.",
                'source_url' => 'https://drive.google.com/drive/folders/sample-gaming-clips',
                'commission_amount' => 10000,
                'view_threshold' => 2000,
                'view_max' => 80000,
                'clipper_limit' => 80,
                'start_at' => Carbon::parse('2026-11-01 00:00:00'),
                'end_at' => Carbon::parse('2026-12-31 23:59:59'),
                'status' => CampaignStatus::Upcoming,
                'created_by' => $creator->id,
            ],
            [
                'title' => 'Promo Diskon Kemerdekaan RI',
                'description' => 'Kampanye promosi musiman kemerdekaan dengan penawaran voucher terbatas.',
                'brief' => "1. Hanya berlaku selama periode promo kemerdekaan.\n2. Infokan kode kupon MERDEKA79.",
                'source_url' => 'https://drive.google.com/drive/folders/sample-promo-assets',
                'commission_amount' => 50000,
                'view_threshold' => 20000,
                'view_max' => 100000,
                'clipper_limit' => 50,
                'start_at' => Carbon::parse('2026-08-10 00:00:00'),
                'end_at' => Carbon::parse('2026-08-20 23:59:59'),
                'status' => CampaignStatus::Inactive,
                'created_by' => $creator->id,
            ],
        ];

        foreach ($campaigns as $campaign) {
            ClipCampaign::updateOrCreate(
                ['title' => $campaign['title']],
                $campaign
            );
        }
    }
}
