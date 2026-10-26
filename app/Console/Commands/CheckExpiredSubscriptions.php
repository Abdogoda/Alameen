<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:expire-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan time-bound course subscriptions and mark expired ones as expired';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();

        $affected = DB::table('course_user')
            ->where('status', 'active')
            ->where('access_expires_at', '<=', $now)
            ->update([
                'status' => 'expired',
                'updated_at' => $now,
            ]);

        $this->info("Completed. Marked {$affected} course subscription(s) as expired.");

        return Command::SUCCESS;
    }
}
