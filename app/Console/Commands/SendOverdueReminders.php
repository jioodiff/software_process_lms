<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendOverdueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lms:send-overdue-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send overdue reminders to n8n webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueBorrowings = Borrowing::with(['mahasiswa', 'items.tool'])
            ->where('status', 'Dipinjam')
            ->whereDate('tgl_rencana_kembali', '<', now()->toDateString())
            ->get();

        if ($overdueBorrowings->isEmpty()) {
            $this->info('No overdue borrowings found.');
            return;
        }

        $webhookUrl = config('services.n8n.webhook_url');
        
        if (!$webhookUrl) {
            $this->error('N8N webhook URL is not configured in services.n8n.webhook_url');
            return;
        }

        $count = 0;
        foreach ($overdueBorrowings as $borrowing) {
            $toolNames = $borrowing->items->pluck('tool.nama_alat')->implode(', ');

            $itemsDetailString = $borrowing->items->map(function ($item) {
                return "- {$item->tool->nama_alat} ({$item->jumlah_unit} unit)";
            })->implode("\n");

            $itemsHtmlTable = '<table style="width: 100%; border-collapse: collapse; margin: 15px 0; border: 1px solid #e2e8f0; font-family: sans-serif;">';
            $itemsHtmlTable .= '<thead><tr>';
            $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: left; border: 1px solid #bfdbfe; font-size: 13px;">Nama Alat</th>';
            $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: center; border: 1px solid #bfdbfe; font-size: 13px;">Unit</th>';
            $itemsHtmlTable .= '</tr></thead><tbody>';
            
            foreach ($borrowing->items as $item) {
                $itemsHtmlTable .= '<tr>';
                $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px;">' . $item->tool->nama_alat . '</td>';
                $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px; text-align: center;">' . $item->jumlah_unit . '</td>';
                $itemsHtmlTable .= '</tr>';
            }
            $itemsHtmlTable .= '</tbody></table>';

            $itemsData = $borrowing->items->map(function ($item) {
                return [
                    'tool_name' => $item->tool->nama_alat,
                    'jumlah_unit' => $item->jumlah_unit,
                ];
            })->toArray();

            try {
                Http::timeout(5)->post($webhookUrl, [
                    'event' => 'borrowing.late',
                    'borrowing_id' => $borrowing->id,
                    'student_name' => $borrowing->mahasiswa->nama_lengkap,
                    'student_email' => $borrowing->mahasiswa->email,
                    'student_whatsapp' => $borrowing->mahasiswa->no_whatsapp,
                    'tool_name' => $toolNames,
                    'items_detail_string' => $itemsDetailString,
                    'items_html_table' => $itemsHtmlTable,
                    'items' => $itemsData,
                    'borrow_date' => $borrowing->tgl_rencana_pinjam->toDateString(),
                    'return_date' => $borrowing->tgl_rencana_kembali->toDateString(),
                    'admin_note' => $borrowing->catatan_admin,
                    'timestamp' => now()->toISOString(),
                ]);
                $count++;
            } catch (\Throwable $e) {
                Log::warning('N8N Webhook failed for borrowing ' . $borrowing->id . ': ' . $e->getMessage());
            }
        }

        $this->info("Sent reminders for $count overdue borrowings.");
    }
}
