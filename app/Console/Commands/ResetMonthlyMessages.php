<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetMonthlyMessages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:reset-monthly-messages';

    /**
     * The console command description.
     */
    protected $description = 'Zero message_count and advance the reset timestamp for paid users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // List of paid subscription tiers – adjust if new tiers are added
        $paidTiers = ['pro', 'advanced'];

        // First moment (UTC) of the current calendar month
        $periodStart = now('UTC')->startOfMonth();

        // Update rows whose reset date is earlier than current month or NULL
        $affected = DB::table('users')
            ->whereIn('subscription_status', $paidTiers)
            ->where(function ($q) use ($periodStart) {
                $q->whereNull('message_count_reset_at')
                  ->orWhere('message_count_reset_at', '<', $periodStart);
            })
            ->update([
                'message_count'          => 0,
                'message_count_reset_at' => $periodStart,
                'updated_at'             => now('UTC'),
            ]);

        $this->info("Rows reset: {$affected}");

        return self::SUCCESS;
    }
}
