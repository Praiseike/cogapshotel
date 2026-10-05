<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class PruneActivityLogs extends Command
{
    protected $signature = 'activity:prune {--days=180 : Keep logs from the last N days}';

    protected $description = 'Delete activity logs older than the retention window';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $count = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        $this->info("Pruned {$count} activity log(s) older than {$days} days.");

        return Command::SUCCESS;
    }
}
