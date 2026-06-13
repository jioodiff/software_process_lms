<?php

namespace App\Traits;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait FiresN8nWebhook
{
    protected function fireWebhook(string $event, Borrowing $borrowing): void
    {
        $webhookUrl = config('services.n8n.webhook_url');
        if (!$webhookUrl) return;

        try {
            $borrowing->loadMissing(['mahasiswa', 'items.tool']);
            $toolNames = $borrowing->items->pluck('tool.nama_alat')->implode(', ');

            Http::withoutVerifying()->timeout(5)->post($webhookUrl, [
                'event' => $event,
                'borrowing_id' => $borrowing->id,
                'student_name' => $borrowing->mahasiswa->nama_lengkap ?? 'Unknown',
                'student_email' => $borrowing->mahasiswa->email ?? 'Unknown',
                'tool_name' => $toolNames,
                'borrow_date' => $borrowing->tgl_rencana_pinjam->toDateString(),
                'return_date' => $borrowing->tgl_rencana_kembali->toDateString(),
                'admin_note' => $borrowing->catatan_admin,
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('N8N Webhook failed: ' . $e->getMessage());
        }
    }
}
