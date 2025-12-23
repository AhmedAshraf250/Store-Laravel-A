<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class DeleteExpireOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:delete-expired {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pending orders older than given days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int) $this->option('days');

        $deletedCount = Order::where('created_at', '<', now()->subDays($days))
            ->where('status', 'pending')
            ->delete();

        if ($deletedCount === 0) {
            $this->info("No expired pending orders found.");
            return Command::SUCCESS;
        }

        $this->info("✅ Deleted {$deletedCount} expired pending orders.");

        return Command::SUCCESS;
    }
}
