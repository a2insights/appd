<?php

namespace App\Jobs;

use App\CarteirinhaStatus;
use App\Models\Carteirinha;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MarcarCarteirinhasVencidas implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Marcando carteirinhas vencidas...', ['date' => now()]);

        Carteirinha::where('data_vencimento', '<', now())
            ->where('status', '!=', 'cancelada')
            ->update(['status' => CarteirinhaStatus::VENCIDA]);
    }
}
