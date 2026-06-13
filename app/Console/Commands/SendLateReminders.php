<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Traits\FiresN8nWebhook;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('borrowings:send-late-reminders')]
#[Description('Send n8n webhook reminders for late borrowings')]
class SendLateReminders extends Command
{
    use FiresN8nWebhook;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lateBorrowings = Borrowing::where('status', 'Dipinjam')
            ->where('tgl_rencana_kembali', '<', today())
            ->get();

        $count = 0;
        foreach ($lateBorrowings as $borrowing) {
            $this->fireWebhook('borrowing.late', $borrowing);
            $count++;
        }

        $this->info("Sent {$count} late reminders.");
    }
}
