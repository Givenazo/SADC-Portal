<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use Carbon\Carbon;

class ArchiveOldVideos extends Command
{
    protected $signature = 'videos:archive-old';
    protected $description = 'Archive videos older than one month';

    public function handle()
    {
        $archived = Video::where('status', 'Published')
            ->whereDate('upload_date', '<', now()->subMonth())
            ->update(['status' => 'Archived']);

        $this->info("Archived {$archived} videos.");
    }
}
