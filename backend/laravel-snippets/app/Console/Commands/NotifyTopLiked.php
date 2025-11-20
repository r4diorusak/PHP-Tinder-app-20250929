<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NotifyTopLiked extends Command
{
    protected $signature = 'notify:top-liked {threshold=50}';
    protected $description = 'Notify admin if any person got liked more than threshold';

    public function handle()
    {
        $threshold = (int) $this->argument('threshold');

        $rows = DB::table('likes')
            ->select('person_id', DB::raw('COUNT(*) as cnt'))
            ->where('type', 'like')
            ->groupBy('person_id')
            ->having('cnt', '>', $threshold)
            ->get();

        if ($rows->isEmpty()) {
            $this->info('No person exceeded threshold.');
            return 0;
        }

        // Build message
        $lines = $rows->map(fn($r) => "Person ID: {$r->person_id} — Likes: {$r->cnt}");
        $body = "People with more than {$threshold} likes:\n" . $lines->implode("\n");

        // Send to admin (configure MAIL_ env vars)
        Mail::raw($body, function ($message) {
            $message->to(config('mail.admin_address') ?? 'admin@example.com')
                    ->subject('Top liked people report');
        });

        $this->info('Admin notified.');
        return 0;
    }
}
