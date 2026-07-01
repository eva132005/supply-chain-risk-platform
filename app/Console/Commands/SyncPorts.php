<?php

namespace App\Console\Commands;

use App\Services\PortService;
use Illuminate\Console\Command;

class SyncPorts extends Command
{
    protected $signature = 'sync:ports';

    protected $description = 'Sync data pelabuhan dari World Port Index ke database';

    public function handle(PortService $portService)
    {
        $this->info('Mengambil data pelabuhan dari World Port Index...');

        $result = $portService->syncPorts();

        if (!$result['success']) {
            $this->error($result['message']);
            return Command::FAILURE;
        }

        $this->info("Selesai! Tersimpan: {$result['created']} pelabuhan | Dilewati: {$result['skipped']}");

        return Command::SUCCESS;
    }
}